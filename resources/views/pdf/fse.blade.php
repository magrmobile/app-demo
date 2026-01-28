<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA SUJETO EXCLUIDO</title>
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
        <tr align="center"><td class="titulo2">FACTURA SUJETO EXCLUIDO</td></tr>
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
                                    <td>{{ $modelo_fact }}</td>
                                </tr>
                                <tr>
                                    <td align="right">Tipo de transmision:</td>
                                    <td>{{ $tipo_trans }}</td>
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
                                    <td align="center">
                                        <span class="titulo">EMISOR</span>
                                        <table class="emisor">
                                            <tr>
                                                <td align="right" style="width: 50%;">Nombre o razon social:</td>
                                                <td style="width: 50%;">{{ $data->emisor->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">NIT:</td>
                                                <td>{{ $data->emisor->nit }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Actividad economica:</td>
                                                <td>{{ $data->emisor->descActividad }}</td>
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
                                        <span class="titulo">SUJETO EXCLUIDO (RECEPTOR)</span>
                                        <table class="receptor">
                                            <tr>
                                                <td align="right" style="width: 50%;">Nombre o razon social:</td>
                                                <td style="width: 50%;">{{ $data->receptor->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Tipo de doc. Identificación:</td>
                                                <td>{{ $tipo_doc }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">N° de doc. Identificación:</td>
                                                <td>{{ $data->receptor->numDocumento }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">No. de Telefono:</td>
                                                <td>{{ $data->receptor->telefono }}</td>
                                            </tr>
                                            <tr>
                                                <td align="right">Dirección:</td>
                                                <td>{{ $data->receptor->direccion->complemento.', '.$dir_rec['desc_depto'].', '.$dir_rec['desc_muni'] }}</td>
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
                                    <td class="celda" style="width: 10%">Precio Unitario</td>
                                    <td class="celda" style="width: 10%">Descuento por item</td>
                                    <td class="celda" style="width: 10%">Ventas</td>
                                </tr>
                                @foreach($data->cuerpoDocumento as $item)
                                <tr>
                                    <td class="celda">{{ $item->numItem }}</td>
                                    <td class="celda">{{ number_format($item->cantidad,2,".",",") }}</td>
                                    <td class="celda">{{ $item->uniMedida }}</td>
                                    <td class="celda">{{ $item->descripcion }}</td>
                                    <td class="celda">{{ number_format($item->precioUni,2) }}</td>
                                    <td class="celda">{{ number_format($item->montoDescu,2) }}</td>
                                    <td class="celda">{{ number_format($item->compra,2) }}</td>
                                </tr>
                                @endforeach
                                <!-- Resumen -->
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" style="text-align: right; padding: 3px;" class="celda">Sumatoria de Ventas:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalCompra,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" style="text-align: right; padding: 3px;" class="celda">Sub-Total:</td>
                                    <td class="celda">{{ number_format($data->resumen->subTotal,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" style="text-align: right; padding: 3px;" class="celda">Total a Pagar:</td>
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
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 20%;">Valor en Letras:</td>
                                    <td>{{ $data->resumen->totalLetras }}</td>
                                </tr>
                                <tr>
                                    <td>Condición de la Operación:</td>
                                    <td>{{ $cond_opera }}</td>
                                </tr>
                                <tr>
                                    <td>Observaciones:</td>
                                    <td>{{ $data->resumen->observaciones }}</td>
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