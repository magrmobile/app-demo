<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COMPROBANTE DE DONACION</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: xx-small;
        }

        table {
            vertical-align: top;
        }

        @page {
            margin: 0 !important;
            padding: 0 !important;
        }

        .titulo {
            font-weight: bold;
        }

        .titulo2 {
            font-weight: bold;
            font-size: 10pt;
        }

        .emisor, .receptor {
            border: 1px solid #000;
            padding: 3px;
            border-radius: 10px;
            width: 100%;
            height: 170px;
        }

        .border {
            border: 1px solid #000;
            padding: 3px;
            border-radius: 5px;
        }

        .bordered {
            border-collapse: collapse;
            table-layout: fixed;
            text-align: center;
        }

        .celda {
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <table style="width: 100%;"> 
        <!-- Encabezado -->
        <tr align="right"><td class="titulo">Ver. 1</td></tr>
        <tr align="center"><td class="titulo2">DOCUMENTO TRIBUTARIO ELECTRONICO</td></tr>
        <tr align="center"><td class="titulo2">COMPROBANTE DE DONACION</td></tr>
        <!-- Identificacion y Sello de Recepcion -->
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <table style="width: 100%;">
                                <tr>
                                    <td align="right">Codigo de Generacion:</td>
                                    <td>{{ $data->identificacion->codigoGeneracion }}</td>
                                </tr>
                                <tr>
                                    <td align="right">Numero de Control:</td>
                                    <td>{{ $data->identificacion->numeroControl }}</td>
                                </tr>
                                <tr>
                                    <td align="right">Sello de Recepcion:</td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                        <td>

                        </td>
                        <td>
                            <table style="width: 100%;">
                                <tr>
                                    <td align="right">Modelo de Facturacion:</td>
                                    <td>{{ $data->identificacion->tipoModelo }}</td>
                                </tr>
                                <tr>
                                    <td align="right">Tipo de transmision:</td>
                                    <td>{{ $data->identificacion->tipoOperacion }}</td>
                                </tr>
                                <tr>
                                    <td align="right">Fecha y Hora de Generacion:</td>
                                    <td>{{ $data->identificacion->fecEmi.' '.$data->identificacion->horEmi }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- Emisor y Receptor -->
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <!-- Emisor -->
                        <td style="vertical-align: baseline;">
                            <table style="width: 100%;">
                                <tr>
                                    <td align="center" >
                                        <span class="titulo">EMISOR</span>
                                        <table class="emisor">
                                            <tr>
                                                <td align="right" style="width: 50%;">Nombre o razon social:</td>
                                                <td style="width: 50%;">{{ $data->emisor->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Actividad economica:</td>
                                                <td>{{ $data->emisor->descActividad }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Tipo de establecimiento:</td>
                                                <td>{{ $data->emisor->tipoEstablecimiento }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Direccion:</td>
                                                <td>{{ $data->emisor->direccion->complemento.', '.$dir_emi['desc_depto'].', '.$dir_emi['desc_muni'] }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Numero de telefono:</td>
                                                <td>{{ $data->emisor->telefono }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Correo electronico:</td>
                                                <td>{{ $data->emisor->correo }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- Receptor -->
                        <td style="vertical-align: baseline;">
                            <table style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <span class="titulo">RECEPTOR</span>
                                        <table class="receptor">
                                            <tr>
                                                <td align="right">Tipo de doc. de Identificacion:</td>
                                                <td>{{ $data->receptor->tipoDocumento }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">N° de doc. de Identificacion:</td>
                                                <td>{{ $data->receptor->numDocumento }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right" style="width: 50%;">Nombre o razon social:</td>
                                                <td style="width: 50%;">{{ $data->receptor->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Direccion:</td>
                                                <td>{{ $data->receptor->direccion->complemento.', '.$dir_rec['desc_depto'].', '.$dir_rec['desc_muni'] }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Numero de telefono:</td>
                                                <td>{{ $data->receptor->telefono }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Correo electronico:</td>
                                                <td>{{ $data->receptor->correo }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- Otros Documentos Asociados -->
        @if(isset($data->otrosDocumentos))
        <tr align="center"><td class="titulo">OTROS DOCUMENTOS ASOCIADOS</td></tr>
        <tr align="center">
           <td>
                <table style="width: 100%;">
                    <tr>
                        <td class="border">
                            <table style="text-align: center; width: 100%;">
                                <tr>
                                    <td style="width: 50%;">Identificacion del documento:</td>
                                    <td>Descripcion:</td>
                                </tr>
                                
                                @foreach($data->otrosDocumentos as $documento)
                                <tr>
                                    <td style="width: 50%;">{{ $documento->descDocumento }}</td>
                                    <td>{{ $documento->detalleDocumento }}</td>
                                </tr>
                                @endforeach
                                
                            </table>
                        </td>
                    </tr>
                </table>
           </td> 
        </tr>
        @endif
        <!-- Cuerpo de Documento -->
        <tr align="center">
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <table class="bordered" style="width: 100%;">
                                <tr>
                                    <td class="celda" style="width: 4%;">N°</td>
                                    <td class="celda" style="width: 6%">Cantidad</td>
                                    <td class="celda" style="width: 5%">Unidad</td>
                                    <td class="celda">Descripción</td>
                                    <td class="celda" style="width: 6%">Precio Unitario</td>
                                    <td class="celda" style="width: 10%">Descuento por item</td>
                                    <td class="celda" style="width: 10%">Otros montos no afectados</td>
                                    <td class="celda" style="width: 6%">Ventas No Sujetas</td>
                                    <td class="celda" style="width: 6%">Ventas Exentas</td>
                                    <td class="celda" style="width: 6%">Ventas Gravadas</td>
                                </tr>
                                @foreach($data->cuerpoDocumento as $item)
                                <tr>
                                    <td class="celda">{{ $item->numItem }}</td>
                                    <td class="celda">{{ number_format($item->cantidad,2,".",",") }}</td>
                                    <td class="celda">{{ $item->uniMedida }}</td>
                                    <td class="celda">{{ $item->descripcion }}</td>
                                    <td class="celda">{{ number_format($item->precioUni,2) }}</td>
                                    <td class="celda">{{ number_format($item->montoDescu,2) }}</td>
                                    <td class="celda">{{ number_format($item->noGravado,2) }}</td>
                                    <td class="celda">{{ number_format($item->ventaNoSuj,2) }}</td>
                                    <td class="celda">{{ number_format($item->ventaExenta,2) }}</td>
                                    <td class="celda">{{ number_format($item->ventaGravada,2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="celda" colspan="2" style="text-align: right; padding: 3px;">SUMA DE VENTAS:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalNoSuj,2) }}</td>
                                    <td class="celda">{{ number_format($data->resumen->totalExenta,2) }}</td>
                                    <td class="celda">{{ number_format($data->resumen->totalGravada,2) }}</td>
                                </tr>
                                <!-- Resumen -->
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Suma de Ventas:</td>
                                    <td class="celda">{{ number_format($data->resumen->subTotalVentas,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Monto global Desc., Rebajas y otros a ventas no sujetas:</td>
                                    <td class="celda">{{ number_format($data->resumen->descuNoSuj,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Monto global Desc., Rebajas y otros a ventas exentas:</td>
                                    <td class="celda">{{ number_format($data->resumen->descuExenta,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Monto global Desc., Rebajas y otros a ventas gravadas:</td>
                                    <td class="celda">{{ number_format($data->resumen->descuGravada,2) }}</td>
                                </tr>
                                @if(isset($data->resumen->tributos))
                                @foreach($data->resumen->tributos as $tributo)
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">{{ $tributo->descripcion }}:</td>
                                    <td class="celda">{{ number_format($tributo->valor,2) }}</td>
                                </tr>
                                @endforeach
                                @endif
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Sub-Total:</td>
                                    <td class="celda">{{ number_format($data->resumen->subTotal,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">IVA Retenido:</td>
                                    <td class="celda">{{ number_format($data->resumen->ivaRete1,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Retención Renta:</td>
                                    <td class="celda">{{ number_format($data->resumen->reteRenta,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Monto Total de la Operación:</td>
                                    <td class="celda">{{ number_format($data->resumen->montoTotalOperacion,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Total Otros montos no afectos:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalNoGravado,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="4" style="text-align: right; padding: 3px;" class="celda">Total a Pagar:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalPagar,2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr align="center">
           <td>
                <table style="width: 100%;">
                    <tr>
                        <td class="border">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 25%;">Valor en Letras:</td>
                                    <td style="width: 25%;">{{ $data->resumen->totalLetras }}</td>
                                    <td style="width: 25%;">Condición de la Operación:</td>
                                    <td style="width: 25%;">{{ $data->resumen->condicionOperacion }}</td>
                                </tr>
                                <tr>
                                    <td>Observaciones:</td>
                                    <td colspan="3">{{ $data->extension->observaciones }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
           </td> 
        </tr>
        <tr align="center">
           <td>
                <table style="width: 100%;">
                    <tr>
                        <td class="border">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 25%;">Responsable por parte del emisor:</td>
                                    <td style="width: 25%;">@if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @endif </td>
                                    <td style="width: 25%;">N° de Documento:</td>
                                    <td style="width: 25%;">@if (Auth::check())
                                        {{ Auth::user()->numDocumento }}
                                    @endif</td>
                                </tr>
                                <tr>
                                    <td style="width: 25%;">Responsable por parte del receptor:</td>
                                    <td style="width: 25%;">{{ $nombre_contacto }}</td>
                                    <td style="width: 25%;">N° de Documento:</td>
                                    <td style="width: 25%;">{{ $numdoc_contacto }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
           </td> 
        </tr>
    </table>
</body>

</html>