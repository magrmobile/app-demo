<?php

namespace App\Services\Billing\Support;

use App\Models\Catalogs\CondicionOperacion;
use App\Models\Catalogs\ModeloFacturacion;
use App\Models\Catalogs\TipoDocumento;
use App\Models\Catalogs\TipoDocumentoReceptor;
use App\Models\Catalogs\TipoEstablecimiento;
use App\Models\Catalogs\TipoTransmision;
use App\Models\Customer;
use Dompdf\Dompdf;
use Illuminate\View\View;

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
        $tipoDocumento = TipoDocumentoReceptor::label($data->receptor->tipoDocumento ?? null);
        $modeloFactura = ModeloFacturacion::label($data->identificacion->tipoModelo ?? null);
        $tipoOperacion = TipoTransmision::label($data->identificacion->tipoOperacion ?? null);
        $tipoEstablecimiento = TipoEstablecimiento::label($data->emisor->tipoEstablecimiento ?? null);
        $condicionOperacion = CondicionOperacion::label($data->resumen->condicionOperacion ?? null);

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

        $path = storage_path('app/sessions/' . $pdfFilename);
        file_put_contents($path, $dompdf->output());

        return $pdfFilename;
    }
}
