<?php

namespace App\Documents;

use Illuminate\Support\Facades\DB;

class FacturaExportacionElectronica extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;
    protected $datosEmisor;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen, $datosEmisor)
    {
        parent::__construct('11');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
        $this->datosEmisor = $datosEmisor;
    }

    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
    }

    public function toArray()
    {
        $data = parent::toArray();

        // Campos Especificos
        $data['emisor']['tipoItemExpor'] = (int)env('DTE_EMISOR_TIPOITEMEXPOR');
        $data['emisor']['recintoFiscal'] = $this->datosEmisor['recintoFiscal'];
        $data['emisor']['regimen'] = $this->datosEmisor['regimen'];

        // Receptor
        $data['receptor']['tipoDocumento'] = "37";
        $data['receptor']['numDocumento'] = $this->datosReceptor['nit'];
        $data['receptor']['nombre'] = $this->datosReceptor['nombre'];
    
        $codPais = isset($this->datosReceptor['codPais']) ? $this->datosReceptor['codPais'] : env('DTE_RECEPTOR_CODPAIS');

        $data['receptor']['codPais'] = (string)$codPais;

        $nomPais = DB::table('cat020')->where('id', $codPais)->first()->valor;
        $data['receptor']['nombrePais'] = $nomPais;

        $data['receptor']['tipoPersona'] = isset($this->datosReceptor['tipoPersona']) ? $this->datosReceptor['tipoPersona'] : env('DTE_RECEPTOR_TIPOPERSONA');

        $data['receptor']['descActividad'] = isset($this->datosReceptor['descActividad']) ? $this->datosReceptor['descActividad'] : env('DTE_RECEPTOR_DESCACTIVIDAD');
        $data['receptor']['nombreComercial'] = $this->datosReceptor['nombreComercial'];
        

        $data['receptor']['complemento'] = isset($this->datosReceptor['complemento']) ? $this->datosReceptor['complemento'] : env('DTE_RECEPTOR_DIRECCION_COMPLEMENTO');
        $data['receptor']['telefono'] = isset($this->datosReceptor['telefono']) ? $this->datosReceptor['telefono'] : env('DTE_RECEPTOR_TELEFONO');
        $data['receptor']['correo'] =  isset($this->datosReceptor['correo']) ? $this->datosReceptor['correo'] : env('DTE_RECEPTOR_EMAIL');
        //$data['receptor']['correo'] = 'pruebas@enerwire.com';
        
        // Otros Documentos
        $data['otrosDocumentos'] = null;
        
        // Venta Tercero
        $data['ventaTercero'] = null;

        $totalGravada = 0;
        $totalDescu = 0;
        $totalNoGravada = 0;
        $seguro = 0;
        $flete = 0;

        $numItem = 1;

        // Cuerpo Documento
        for($i=0; $i < count($this->detalleItems); $i++) {
            if($this->detalleItems[$i]['unidad'] != 'NG') {
                $cat014 = DB::table('cat014')
                ->where('id', $this->detalleItems[$i]['unidad'])
                ->first();

                if($cat014){
                    $unidad = $cat014->id;
                } else {
                    $unidad = 99;
                }

                $item = [
                    'numItem' => $numItem,
                    'codigo' => null,
                    'descripcion' => (string) $this->detalleItems[$i]['item'],
                    'cantidad' => (isset($this->detalleItems[$i]['cantidad']) || is_string($this->detalleItems[$i]['cantidad']))  ? (float) $this->detalleItems[$i]['cantidad'] : 1,
                    'uniMedida' => (int) $unidad,
                    'precioUni' => (float) $this->detalleItems[$i]['precio'],
                    'montoDescu' => 0,
                    'ventaGravada' => (float) $this->detalleItems[$i]['monto'],
                    'tributos' => array("C3"),
                    'noGravado' => 0, 
                ];

                $totalGravada += $item['ventaGravada'];
                $totalDescu += $item['montoDescu'];
                $totalNoGravada += $item['noGravado'];

                $data['cuerpoDocumento'][] = $item;

                $numItem += 1;
            } else {
                $is_seguro = str_contains($this->detalleItems[$i]['descripcion'],'SEGURO');
                $is_transporte = str_contains($this->detalleItems[$i]['descripcion'],'TRANSPORTE');

                if($is_seguro) {
                    $seguro = round((float)$this->detalleItems[$i]['monto'],2);
                    $item = [];
                } elseif($is_transporte) {
                    $flete = round((float)$this->detalleItems[$i]['monto'],2);
                    $item = [];
                } else {
                    $item = [
                        'numItem' => $numItem,
                        'codigo' => null,
                        'descripcion' => (string) $this->detalleItems[$i]['descripcion'],
                        //'cantidad' => (isset($this->detalleItems[$i]['cantidad']) || is_string($this->detalleItems[$i]['cantidad']))  ? (float) $this->detalleItems[$i]['cantidad'] : 1,
                        'cantidad' => 1,
                        'uniMedida' => 99,
                        'precioUni' => 0,
                        'montoDescu' => 0,
                        'ventaGravada' => 0,
                        'tributos' => null,
                        'noGravado' => (float) $this->detalleItems[$i]['monto'], 
                    ];

                    $totalGravada += $item['ventaGravada'];
                    $totalDescu += $item['montoDescu'];
                    $totalNoGravada += $item['noGravado'];

                    $data['cuerpoDocumento'][] = $item;
                    
                    $numItem += 1;
                }
            }
        }

        // Resumen
        $data['resumen']['descuento'] = 0;

        $data['resumen']['porcentajeDescuento'] = 0;
        $data['resumen']['totalDescu'] = $totalDescu;

        $montoTotalOperacion = $totalGravada + $seguro + $flete - $totalDescu;
        $montoTotal = $montoTotalOperacion + $totalNoGravada;

        $data['resumen']['montoTotalOperacion'] = round($montoTotalOperacion, 2);
        $data['resumen']['totalGravada'] = round($totalGravada, 2);
        $data['resumen']['totalNoGravado'] = round($totalNoGravada, 2);
        $data['resumen']['totalPagar'] = round($montoTotal, 2);
        $data['resumen']['totalLetras'] = self::numeroALetras($montoTotal);
        //$data['resumen']['totalLetras'] = "";

        $condicion = DB::table('qb_terms')
            ->where('terms', $this->detalleResumen['condicion'])
            ->first();

        $data['resumen']['condicionOperacion'] = (int) $condicion->codigo_mh;

        $pagos = [
            'codigo' => "01",
            'montoPago' => $data['resumen']['totalPagar'],
            'plazo' => $condicion->codigo_plazo,         
            'referencia' => "",
            'periodo' => $condicion->periodo
        ];

        $data['resumen']['seguro'] = $seguro;
        $data['resumen']['flete'] = $flete;

        //$data['resumen']['codIncoterms'] = "04";

        $descIncoterms = DB::table('cat031')->where('id', $this->detalleResumen['codIncoterms'])->first();
        $data['resumen']['codIncoterms'] = $this->detalleResumen['codIncoterms'];
        $data['resumen']['descIncoterms'] = $descIncoterms->valor;

        $data['resumen']['pagos'][] = $pagos;
        $data['resumen']['numPagoElectronico'] = null;

        // Apendice
        $data['apendice'] = null;

        //dd($data);
        
        return $data;
    }
}