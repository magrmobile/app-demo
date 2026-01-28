<?php

namespace App\Documents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprobanteCreditoFiscalElectronico extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen)
    {
        parent::__construct('03');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // Campos Especificos

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
        
        $data['documentoRelacionado'] = null;
        
        // Documento Relacionado
        /*$docRel = $this->detalleResumen['documentoRelacionado'];
        if(count($docRel) > 0) {
            for($i=0; $i < count($docRel); $i++) {
                $data['documentoRelacionado'][$i]["numeroDocumento"] = $docRel[$i]['numdoc'];

                $date = str_replace('/','-',$docRel[$i]['date']);
            }
        }*/
        
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
                'numeroDocumento' => null,
                'codigo' => null,
                'codTributo' => null,
                'descripcion' => $this->detalleItems[$i]['descripcion'],
                'cantidad' => (float)$this->detalleItems[$i]['cantidad'],
                'uniMedida' => (int) $unidad,
                'precioUni' => (float)$this->detalleItems[$i]['precio'],
                'montoDescu' => 0,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => (float)$this->detalleItems[$i]['monto'],
                'tributos' => array("20"),
                'psv' => 0,
                'noGravado' => 0, 
            ];
            
            $data['cuerpoDocumento'][] = $item;
        }

        // Resumen
        $monto = round($this->detalleResumen['monto'],2);
        $monto_iva = round($this->detalleResumen['monto'] * 0.13, 2);

        $data['resumen']['totalNoSuj'] = 0;
        $data['resumen']['totalExenta'] = 0;
        $data['resumen']['totalGravada'] = $monto;
        $data['resumen']['subTotalVentas'] = $monto;
        $data['resumen']['descuNoSuj'] = 0;
        $data['resumen']['descuExenta'] = 0;
        $data['resumen']['descuGravada'] = 0;
        $data['resumen']['porcentajeDescuento'] = 0;
        $data['resumen']['totalDescu'] = 0;

        $tributos = [
            'codigo' => "20",
            'descripcion' => "Impuesto al Valor Agregado 13%",
            'valor' => $monto_iva,
        ];

        $montoTotal = round($monto + $monto_iva, 2);

        if($this->datosReceptor['category_id'] != 1 && $monto >= 100) {
            $ivaPerci1 = round($monto * 0.01, 2);
        } else {
            $ivaPerci1 = 0;
        }
        
        $data['resumen']['tributos'][] = $tributos;
        $data['resumen']['subTotal'] = $monto;
        $data['resumen']['ivaPerci1'] = $ivaPerci1;
        $data['resumen']['ivaRete1'] = 0;
        $data['resumen']['reteRenta'] = 0;
        $data['resumen']['montoTotalOperacion'] = round($montoTotal, 2);
        $data['resumen']['totalNoGravado'] = 0;
        $totalPagar = round($montoTotal + $ivaPerci1, 2);
        $data['resumen']['totalPagar'] = round($totalPagar, 2);
        $data['resumen']['totalLetras'] = self::numeroALetras($totalPagar);
        //$data['resumen']['totalLetras'] = "";
        $data['resumen']['saldoFavor'] = 0;

        $condicion = DB::table('qb_terms')
            ->where('terms', $this->detalleResumen['condicion'])
            ->first();

        $data['resumen']['condicionOperacion'] = (int) $condicion->codigo_mh;

        $pagos = [
            'codigo' => "01",
            'montoPago' => $totalPagar,
            'plazo' => $condicion->codigo_plazo,
            'referencia' => "",
            'periodo' => $condicion->periodo
        ];

        $data['resumen']['pagos'][] = $pagos;
        $data['resumen']['numPagoElectronico'] = null;

        // Extension
        $data['extension']['nombEntrega'] = Auth::user()->name;
        $data['extension']['docuEntrega'] = Auth::user()->numDocumento;
        $data['extension']['nombRecibe'] = $this->datosReceptor['nombre_contacto'];
        $data['extension']['docuRecibe'] = $this->datosReceptor['numdoc_contacto'];
        $data['extension']['placaVehiculo'] = null;

        // Apendice
        $data['apendice'] = null;

        return $data;
    }
}