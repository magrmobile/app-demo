<?php

namespace App\Services\Billing;

use App\Documents\ComprobanteCreditoFiscalElectronico;
use App\Documents\ComprobanteRetencionElectronico;
use App\Documents\FacturaElectronica;
use App\Documents\FacturaExportacionElectronica;
use App\Documents\FacturaSujetoExcluidoElectronica;
use App\Documents\NotaCreditoElectronica;
use App\Documents\NotaDebitoElectronica;
use App\Documents\NotaRemisionElectronica;
use App\Models\Catalogs\ActividadEconomica;
use App\Models\Catalogs\Departamento;
use App\Models\Catalogs\QuickBooksUnits;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;

class BillingExcelProcessor
{
    public function process(string $filePath, string $type, array $context): string 
    {
        $rows = $this->readCsv($filePath);

        $receptor = $this->resolveReceptor($rows);
        $receptorData = $this->mapReceptor($receptor);
        $detalleLineas = $this->parseDetalleLines($rows);
        $detalle = $this->buildDetalle($detalleLineas);

        return $this->buildDocumento(
            type: $type,
            receptor: $receptorData,
            detalleItems: $detalle['items'],
            detalleResumen: $detalle['resumen'],
            context: $context,
            headers: $rows[0],
            rows: $rows,
        );
    }

    // CSV
    private function readCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $string = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');

        return $this->parseCsv($string);
    }

    private function parseCsv(string $csv, string $delimiter = ','): array
    {
        return array_map(
            fn ($line) => str_getcsv($line, $delimiter),
            preg_split('/\r\n|\n|\r/', trim($csv))
        );
    }

    // Cliente / Receptor
    private function resolveReceptor(array $rows): Customer
    {
        $nit = trim(str_replace('-', '', $rows[2][2] ?? ''))
            ?: trim(str_replace('-', '', $rows[3][2] ?? ''));

        $nrc = trim(str_replace('-', '', $rows[2][3] ?? ''))
            ?: trim(str_replace('-', '', $rows[3][3] ?? ''));

        if (!$nit && !$nrc) {
            throw new Exception("No se encontró NIT o NRC del receptor en el archivo.");
        }

        $customer = Customer::where('nit', $nit)
            ->orWhere('nrc', $nrc)
            ->first();

        if (!$customer) {
            throw new Exception("Cliente no registrado (NIT: $nit / NRC: $nrc)");
        }

        return $customer;
    }

    private function mapReceptor(Customer $c): array
    {
        $descActividad = $c->codActividad
            ? ActividadEconomica::where('id', $c->codActividad)->value('valor')
            : null;

        return [
            'nit' => $c->nit,
            'nrc' => $c->nrc,
            'nombre' => $c->nombre,
            'codActividad' => $c->codActividad,
            'descActividad' => $descActividad,
            'nombreComercial' => $c->nombreComercial,
            'departamento' => $c->departamento->id,
            'municipio' => $c->municipio->id,
            'complemento' => $c->complemento,
            'codPais' => $c->codPais,
            'codDomiciliado' => $c->codDomiciliado,
            'codigoMH' => $c->codigoMH,
            'puntoVentaMH' => $c->puntoVentaMH,
            'bienTitulo' => $c->bienTitulo,
            'tipoPersona' => $c->tipoPersona,
            'telefono' => $c->telefono,
            'correo' => $c->correo,
            'category_id' => $c->category_id,
            'nombre_contacto' => $c->nombre_contacto,
            'tipoc_contacto' => $c->tipoc_contacto,
            'numdoc_contacto' => $c->numdoc_contacto,
        ];
    }

    // Detalle
    private function parseDetalleLines(array $rows): array
    {
        $headers = $rows[0];
        $lines = [];

        for ($i = 2; $i < count($rows); $i++) {
            if (in_array('Total ' . ($rows[1][0] ?? ''), $rows[$i])) {
                break;
            }

            $row = [];
            foreach ($rows[$i] as $idx => $value) {
                $row[$headers[$idx] ?? $idx] = $value;
            }

            if (!empty($row['Type'])) {
                $lines[] = $row;
            }
        }

        return $lines;
    }

    private function buildDetalle(array $lines): array
    {
        $items = [];
        $docs = [];
        $terms = [];
        $total = 0;

        foreach ($lines as $row) {
            $monto = is_numeric($row['Amount'] ?? null)
                ? (float) $row['Amount']
                : (float) ($row['Credit'] ?? 0);

            $precio = $row['Sales Price']
                ?? $row['Cost Price']
                ?? $monto;

            $items[] = [
                'item' => trim(($row['Memo'] ?? $row['Item']) . ' ' . ($row['COLOR'] ?? '')),
                'descripcion' => trim(($row['Memo'] ?? $row['Item Description']) . ' ' . ($row['COLOR'] ?? '')),
                'cantidad' => $row['Qty'] ?? null,
                'unidad' => $this->resolveUnidad($row),
                'precio' => $precio,
                'monto' => $monto,
                'numdoc' => $row['Num'],
                'date' => $row['Date'],
                'due_date' => $row['Due Date'],
            ];

            $total += $monto;

            $docs[] = [
                'numdoc' => $row['Num'],
                'date' => $row['Date'],
                'due_date' => $row['Due Date'],
            ];

            $terms[] = ['terms' => $row['Terms']];
        }

        return [
            'items' => $items,
            'resumen' => [
                'monto' => $total,
                'documentoRelacionado' => array_unique($docs, SORT_REGULAR),
                'condicion' => array_unique($terms, SORT_REGULAR)[0]['terms'] ?? null,
            ] 
        ];
    }

    private function resolveUnidad(array $row): string
    {
        if (!isset($row['U/M']) || $row['U/M'] === null || $row['U/M'] === 'ea') {
            return 'NG';
        }

        $qb = QuickBooksUnits::find($row['U/M']);
        return $qb?->codigo_mh ?? '99';
    }

    // Documento Final (JSON)
    private function buildDocumento(
        string $type,
        array $receptor,
        array $detalleItems,
        array $detalleResumen,
        array $context,
        array $headers,
        array $rows,
    ): string {
        $observaciones = $context['comments'] ?? '';

        switch ($type) {
            case '01':
                $doc = new FacturaElectronica($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '03':
                $doc = new ComprobanteCreditoFiscalElectronico($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '04':
                $doc = new NotaRemisionElectronica($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                unset($data['otrosDocumentos']);
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '05':
                $doc = new NotaCreditoElectronica($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '06':
                $doc = new NotaDebitoElectronica($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '07':
                $doc = new ComprobanteRetencionElectronico($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['documentoRelacionado'],
                    $data['ventaTercero'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                break;
            case '11':
                $datosEmisor = [
                    'recintoFiscal' => $context['recintoFiscal'] ?? null,
                    'regimen' => $context['regimen'] ?? null,
                ];

                if (in_array('INCOTERMS', $headers)) {
                    $cod = $rows[2][17] ?? null;
                    if ($cod) {
                        $inc = DB::table('cat031')->where('codigo', $cod)->first();
                        if ($inc) {
                            $detalleResumen['codIncoterms'] = $inc->id;
                            $detalleResumen['descIncoterms'] = $inc->valor;
                        }
                    }
                }

                $doc = new FacturaExportacionElectronica($receptor, $detalleItems, $detalleResumen, $datosEmisor);
                $data = $doc->toArray();
                unset($data['documentoRelacionado'], $data['extension']);
                $data['resumen']['observaciones'] = $observaciones;
                break;
            case '14':
                $doc = new FacturaSujetoExcluidoElectronica($receptor, $detalleItems, $detalleResumen);
                $data = $doc->toArray();
                unset(
                    $data['emisor']['nombreComercial'],
                    $data['emisor']['tipoEstablecimiento'],
                    $data['documentoRelacionado'],
                    $data['otrosDocumentos'],
                    $data['ventaTercero'],
                    $data['extension']
                );
                $data['resumen']['observaciones'] = $observaciones;
                break;
            default:
                throw new Exception("Tipo DTE invalido: {$type}");
        }

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}