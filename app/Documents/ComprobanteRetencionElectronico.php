<?php

namespace App\Documents;

use Illuminate\Support\Facades\Auth;

class ComprobanteRetencionElectronico extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen)
    {
        parent::__construct('07');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // Campos Especificos

        // Emisor
        $data['emisor']['codigoMH'] = env('DTE_EMISOR_CODESTABLEMH');
        $data['emisor']['codigo'] = env('DTE_EMISOR_CODESTABLE');
        $data['emisor']['puntoVentaMH'] = env('DTE_EMISOR_CODPUNTOVENTAMH');
        $data['emisor']['puntoVenta'] = env('DTE_EMISOR_CODPUNTOVENTA');

        // Receptor
        $data['receptor']['tipoDocumento'] = '36';
        $data['receptor']['numDocumento'] = isset($this->datosReceptor['nit']) ? $this->datosReceptor['nit'] : env('DTE_RECEPTOR_NIT');
        $data['receptor']['nrc'] = isset($this->datosReceptor['nrc']) ? $this->datosReceptor['nrc'] : env('DTE_RECEPTOR_NRC');
        $data['receptor']['nombre'] = isset($this->datosReceptor['nombre']) ? $this->datosReceptor['nombre'] : env('DTE_RECEPTOR_NOMBRE');
        $data['receptor']['codActividad'] = isset($this->datosReceptor['codActividad']) ? $this->datosReceptor['codActividad'] : env('DTE_RECEPTOR_CODACTIVIDAD');
        $data['receptor']['descActividad'] = isset($this->datosReceptor['descActividad']) ? $this->datosReceptor['descActividad'] : env('DTE_RECEPTOR_DESCACTIVIDAD');
        $data['receptor']['nombreComercial'] = null;
        $data['receptor']['direccion']['departamento'] = isset($this->datosReceptor['departamento']) ? $this->datosReceptor['departamento'] : env('DTE_RECEPTOR_DIRECCION_DEPARTAMENTO');
        $data['receptor']['direccion']['municipio'] = isset($this->datosReceptor['municipio']) ? $this->datosReceptor['municipio'] : env('DTE_RECEPTOR_DIRECCION_MUNICIPIO');
        $data['receptor']['direccion']['complemento'] = isset($this->datosReceptor['complemento']) ? $this->datosReceptor['complemento'] : env('DTE_RECEPTOR_DIRECCION_COMPLEMENTO');
        $data['receptor']['telefono'] = isset($this->datosReceptor['telefono']) ? $this->datosReceptor['telefono'] : env('DTE_RECEPTOR_TELEFONO');
        $data['receptor']['correo'] =  isset($this->datosReceptor['correo']) ? $this->datosReceptor['correo'] : env('DTE_RECEPTOR_EMAIL');
        //$data['receptor']['correo'] = 'pruebas@enerwire.com';
        
        // Otros Documentos
        $data['otrosDocumentos'] = null;
        
        // Venta Tercero
        $data['ventaTercero'] = null;

        // Cuerpo Documento
        for($i=0; $i < count($this->detalleItems); $i++) {
            $fechaemision = $this->detalleItems[$i]['date'];
            $date = str_replace('/','-',$fechaemision);

            $item = [
                'numItem' => $i + 1,
                'tipoDte' => "03",
                'tipoDoc' => 1,
                'numDocumento' => $this->detalleItems[$i]['numdoc'],
                'fechaEmision' => date('Y-m-d',strtotime($date)),
                'ivaRetenido' => 0.01, // Cambiar
                'codigoRetencionMH' => "22",
                'montoSujetoGrav' => 1, // Cambiar
                'descripcion' => $this->detalleItems[$i]['descripcion'],
            ];
            
            $data['cuerpoDocumento'][] = $item;
        }

        // Resumen
        $monto = round($this->detalleResumen['monto'],2);
        //$monto_iva = round($this->detalleResumen['monto'] * 0.13, 2);
        $data['resumen']['totalSujetoRetencion'] = 0.01;
        $data['resumen']['totalIVAretenido'] = 0.01;
        $data['resumen']['totalIVAretenidoLetras'] = "";


        /*$tributos = [
            'codigo' => "20",
            'descripcion' => "Impuesto al Valor Agregado 13%",
            'valor' => $monto_iva,
        ];*/

        $montoTotal = round($monto, 2);

        // $data['resumen']['totalLetras'] = self::numeroALetras($montoTotal);

        // Extension
        $data['extension']['nombEntrega'] = Auth::user()->name;
        $data['extension']['docuEntrega'] = Auth::user()->numDocumento;
        $data['extension']['nombRecibe'] = $this->datosReceptor['nombre_contacto'];
        $data['extension']['docuRecibe'] = $this->datosReceptor['numdoc_contacto'];

        // Apendice
        $data['apendice'] = null;

        return $data;
    }
}