<?php

namespace App\Documents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaCreditoElectronica extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen)
    {
        parent::__construct('05');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // Campos Especificos
        $docref = "";
        $fechaemision = "";
        
        // Receptor
        $data['receptor']['nit'] = isset($this->datosReceptor['nit']) ? $this->datosReceptor['nit'] : env('DTE_RECEPTOR_NIT');
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
            $cat014 = DB::table('cat014')
            ->where('id', $this->detalleItems[$i]['unidad'])
            ->first();

            if($cat014){
                $unidad = $cat014->id;
            } else {
                $unidad = 99;
            }

            $item = [
                'numItem' => $i + 1,
                'tipoItem' => 1,
                'numeroDocumento' => $this->detalleItems[$i]['numdoc'],
                'codigo' => null,
                'codTributo' => null,
                'descripcion' => $this->detalleItems[$i]['descripcion'],
                'cantidad' => abs((float) $this->detalleItems[$i]['cantidad']),
                'uniMedida' => (int) $unidad,
                'precioUni' => (float) $this->detalleItems[$i]['precio'],
                'montoDescu' => 0,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => abs($this->detalleItems[$i]['monto']),
                'tributos' => array("20"),
            ];
            
            $data['cuerpoDocumento'][] = $item;
            $docref = $item['numeroDocumento'];
            $fechaemision = $this->detalleItems[$i]['date'];
        }

        $date = str_replace('/','-',$fechaemision);

        $data['documentoRelacionado'][] = [
            'tipoDocumento' => "03",
            'tipoGeneracion' => 1,
            'numeroDocumento' => $docref,
            'fechaEmision' => date('Y-m-d',strtotime($date))
        ];

        // Resumen
        $monto = round($this->detalleResumen['monto'],2);
        $monto_iva = round($this->detalleResumen['monto'] * 0.13, 2);

        $data['resumen']['totalNoSuj'] = 0;
        $data['resumen']['totalExenta'] = 0;
        $data['resumen']['totalGravada'] = abs($monto);
        $data['resumen']['subTotalVentas'] = abs($monto);
        $data['resumen']['descuNoSuj'] = 0;
        $data['resumen']['descuExenta'] = 0;
        $data['resumen']['descuGravada'] = 0;

        $data['resumen']['totalDescu'] = 0;

        $tributos = [
            'codigo' => "20",
            'descripcion' => "Impuesto al Valor Agregado 13%",
            'valor' => abs($monto_iva),
        ];

        $montoTotal = round($monto + $monto_iva, 2);

        $data['resumen']['tributos'][] = $tributos;
        $data['resumen']['subTotal'] = abs($monto);
        $data['resumen']['ivaPerci1'] = 0;
        $data['resumen']['ivaRete1'] = 0;
        $data['resumen']['reteRenta'] = 0;
        $data['resumen']['montoTotalOperacion'] = abs($montoTotal);
  
        $data['resumen']['totalLetras'] = self::numeroALetras(abs($montoTotal));
        //$data['resumen']['totalLetras'] = "";

        $condicion = DB::table('qb_terms')
            ->where('terms', $this->detalleResumen['condicion'])
            ->first();

        $data['resumen']['condicionOperacion'] = (int) $condicion->codigo_mh;

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