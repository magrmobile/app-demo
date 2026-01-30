<?php

namespace App\Services\Billing\Support;

use App\Models\Catalogs\CondicionOperacion;
use App\Models\Catalogs\Departamento;
use App\Models\Catalogs\ModeloFacturacion;
use App\Models\Catalogs\Municipio;
use App\Models\Catalogs\TipoDocumentoReceptor;
use App\Models\Catalogs\TipoEstablecimiento;
use App\Models\Catalogs\TipoTransmision;
use App\Models\Customer;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Genera y guarda el PDF en storage/app/sessions
     */
    public function generateAndStore(
        string $json,
        string $type,
        string $pdfTemplate,
        string $pdfFilename
    ): string {
        $data = json_decode($json);

        if (!$data) {
            throw new \RuntimeException('JSON invalido para generar PDF');
        } 

        // Emisor / Receptor
        $dirEmisor = $this->resolveDireccion($data->emisor->direccion ?? null);
        $dirReceptor = $this->resolveDireccion($data->receptor->direccion ?? null);

        // Catalogos MH
        $tipoDocumento = TipoDocumentoReceptor::find($data->receptor->tipoDocumento ?? null)->valor;
        
        $modeloFactura = ModeloFacturacion::find($data->identificacion->tipoModelo ?? null)->valor;
        $tipoOperacion = TipoTransmision::find($data->identificacion->tipoOperacion ?? null)->valor;
        $tipoEstablecimiento = TipoEstablecimiento::find($data->emisor->tipoEstablecimiento ?? null)->valor;
        $condicionOperacion = CondicionOperacion::find($data->resumen->condicionOperacion ?? null)->valor;

        // Casos Especiales
        $nombreContacto = null;
        $numdocContacto = null;

        if (isset($data->receptor->tipoDocumento) && $data->receptor->tipoDocumento === '36') {
            $customer = Customer::where('nit', $data->receptor->numDocumento)->first();
            $nombreContacto = $customer->nombre_contacto;
            $numdocContacto = $customer->numdoc_contacto;
        }

        // Render Blade
        $html = View::make($pdfTemplate, [
            'data' => $data,
            'dir_emi' => $dirEmisor,
            'dir_rec' => $dirReceptor,
            'tipo_doc' => $tipoDocumento,
            'modelo_fact' => $modeloFactura,
            'tipo_trans' => $tipoOperacion,
            'tipo_establec' => $tipoEstablecimiento,
            'cond_opera' => $condicionOperacion,
            'nombre_contacto' => $nombreContacto,
            'numdoc_contacto' => $numdocContacto,
        ])->render();

        // Dompdf
        $dompdf = new Dompdf([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->render();

        Storage::disk('local')->put(
            'session/' . $pdfFilename,
            $dompdf->output()
        );

        return $pdfFilename;
    }

    private function resolveDireccion(?object $direccion): ?array
    {
        if (!$direccion) {
            return null;
        }

        return [
            'desc_depto' => Departamento::find($direccion->departamento ?? null)->valor,
            'desc_muni' => Municipio::where('departamento_id', $direccion->departamento ?? null)
                ->where('id', $direccion->municipio ?? null)
                ->first()->valor,
        ];
    }
}
