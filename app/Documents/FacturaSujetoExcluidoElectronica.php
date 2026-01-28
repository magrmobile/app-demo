<?php

namespace App\Documents;

use Illuminate\Support\Facades\DB;

class FacturaSujetoExcluidoElectronica extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen)
    {
        parent::__construct('14');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // Campos Especificos

        // Receptor
        $data['receptor']['tipoDocumento'] = '36';
        $data['receptor']['numDocumento'] = isset($this->datosReceptor['nit']) ? $this->datosReceptor['nit'] : env('DTE_RECEPTOR_NIT');;
        $data['receptor']['nombre'] = isset($this->datosReceptor['nombre']) ? $this->datosReceptor['nombre'] : env('DTE_RECEPTOR_NOMBRE');
        $data['receptor']['codActividad'] = isset($this->datosReceptor['codActividad']) ? $this->datosReceptor['codActividad'] : env('DTE_RECEPTOR_CODACTIVIDAD');
        $data['receptor']['descActividad'] = isset($this->datosReceptor['descActividad']) ? $this->datosReceptor['descActividad'] : env('DTE_RECEPTOR_DESCACTIVIDAD');
        $data['receptor']['direccion']['departamento'] = isset($this->datosReceptor['departamento']) ? $this->datosReceptor['departamento'] : env('DTE_RECEPTOR_DIRECCION_DEPARTAMENTO');
        $data['receptor']['direccion']['municipio'] = isset($this->datosReceptor['municipio']) ? $this->datosReceptor['municipio'] : env('DTE_RECEPTOR_DIRECCION_MUNICIPIO');
        $data['receptor']['direccion']['complemento'] = isset($this->datosReceptor['complemento']) ? $this->datosReceptor['complemento'] : env('DTE_RECEPTOR_DIRECCION_COMPLEMENTO');
        $data['receptor']['telefono'] = isset($this->datosReceptor['telefono']) ? $this->datosReceptor['telefono'] : env('DTE_RECEPTOR_TELEFONO');
        $data['receptor']['correo'] =  isset($this->datosReceptor['correo']) ? $this->datosReceptor['correo'] : env('DTE_RECEPTOR_EMAIL');
        //$data['receptor']['correo'] = 'pruebas@enerwire.com';
        
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
                'codigo' => null,
                'descripcion' => $this->detalleItems[$i]['descripcion'],
                'cantidad' => (float) $this->detalleItems[$i]['cantidad'],
                'uniMedida' => (int) $unidad,
                'precioUni' => (float) $this->detalleItems[$i]['precio'],
                'montoDescu' => 0,
                'compra' => (float) $this->detalleItems[$i]['monto'],
            ];
            
            $data['cuerpoDocumento'][] = $item;
        }

        // Resumen
        $monto = round($this->detalleResumen['monto'],2);

        $data['resumen']['totalCompra'] = $monto;
        $data['resumen']['subTotal'] = $monto;

        $data['resumen']['descu'] = 0;
        $data['resumen']['totalDescu'] = 0;


        $montoTotal = round($monto, 2);

        $data['resumen']['ivaRete1'] = 0;
        $data['resumen']['reteRenta'] = 0;
        //$data['resumen']['montoTotalOperacion'] = round($montoTotal + $ivaPerci1, 2);

        $data['resumen']['totalPagar'] = $montoTotal;
        $data['resumen']['totalLetras'] = self::numeroALetras($montoTotal);
        //$data['resumen']['totalLetras'] = "";

        $condicion = DB::table('qb_terms')
            ->where('terms', $this->detalleResumen['condicion'])
            ->first();

        $data['resumen']['condicionOperacion'] = (int) $condicion->codigo_mh;

        $pagos = [
            'codigo' => "01",
            'montoPago' => $montoTotal,
            'plazo' => null,
            'referencia' => "",
            'periodo' => null
        ];

        $data['resumen']['pagos'][] = $pagos;

        // Apendice
        $data['apendice'] = null;

        return $data;
    }
}