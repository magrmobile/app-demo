<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COMPROBANTE DE RETENCION ELECTRONICA</title>
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
            padding: 10px;
            border-radius: 10px;
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
        <tr align="center"><td class="titulo2">COMPROBANTE DE RETENCION</td></tr>
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
                                        <span class="titulo" style="padding: 5px;">AGENTE DE RETENCIÓN</span>
                                        <table class="emisor">
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">Nombre o razon social:</td>
                                                <td>{{ $data->emisor->nombre }}</td>
                                                <td style="text-align: right;">Nombre comercial:</td>
                                                <td>{{ $data->emisor->nombreComercial }}</td>
                                                <td style="text-align: right;">NIT:</td>
                                                <td>{{ $data->emisor->nit }}</td>
                                            </tr>
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">NRC:</td>
                                                <td>{{ $data->emisor->nrc }}</td>
                                                <td style="text-align: right;">Actividad economica:</td>
                                                <td>{{ $data->emisor->descActividad }}</td>
                                                <td style="text-align: right;">Direccion:</td>
                                                <td>{{ $data->emisor->direccion->complemento.', '.$dir_emi['desc_depto'].', '.$dir_emi['desc_muni'] }}</td>        
                                            </tr>
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">Numero de telefono:</td>
                                                <td>{{ $data->emisor->telefono }}</td>
                                                <td style="text-align: right;">Correo electronico:</td>
                                                <td>{{ $data->emisor->correo }}</td>
                                                <td style="text-align: right;">Tipo de establecimiento:</td>
                                                <td>{{ $tipo_establec }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>   
                    </tr>
                    <tr>
                        <!-- Receptor -->
                        <td style="vertical-align: baseline;">
                            <table style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <span class="titulo">SUJETO DE RETENCION (RECEPTOR)</span>
                                        <table class="receptor">
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">Nombre o razon social:</td>
                                                <td>{{ $data->receptor->nombre }}</td>
                                                <td style="text-align: right;">Tipo de doc. de Identificacion:</td>
                                                <td>{{ $data->receptor->tipoDocumento }}</td>
                                                <td style="text-align: right;">NRC:</td>
                                                <td>{{ $data->receptor->nrc }}</td>
                                            </tr>
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">Actividad economica:</td>
                                                <td>{{ $data->receptor->descActividad }}</td>
                                                <td style="text-align: right;">N° de doc. de Identificacion:</td>
                                                <td>{{ $data->receptor->numDocumento }}</td>
                                                <td style="text-align: right;">Nombre comercial:</td>
                                                <td>{{ $data->receptor->nombreComercial }}</td>
                                            </tr>
                                            <tr style="vertical-align: top;">
                                                <td style="text-align: right;">Direccion:</td>
                                                <td>{{ $data->receptor->direccion->complemento.', '.$dir_rec['desc_depto'].', '.$dir_rec['desc_muni'] }}</td>
                                                <td style="text-align: right;">Correo electronico:</td>
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
        <!-- Documentos Relacionados -->
        @if(isset($data->documentoRelacionado))
        <tr align="center"><td class="titulo">DOCUMENTOS RELACIONADOS</td></tr>
        <tr align="center">
           <td>
                <table style="width: 100%;">
                    <tr>
                        <td class="border">
                            <table style="text-align: center; width: 100%">
                                <tr>
                                    <td style="width: 33%;">Tipo de documento:</td>
                                    <td style="width: 33%;">N° de documento:</td>
                                    <td>Fecha de documento:</td>
                                </tr>
                                
                                @foreach($data->documentoRelacionado as $docRel)
                                <tr>
                                    <td style="width: 33%;">{{ $docRel->tipoDocumento }}</td>
                                    <td style="width: 33%;">{{ $docRel->numeroDocumento }}</td>
                                    <td>{{ $docRel->fechaEmision }}</td>
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
                                    <td class="celda">N°</td>
                                    <td class="celda">Tipo de Doc. Relacionado</td>
                                    <td class="celda">No. del Doc. Relacionado</td>
                                    <td class="celda">Fecha del doc.</td>
                                    <td class="celda">Descripcion</td>
                                    <td class="celda">Monto Sujeto a Retencion</td>
                                    <td class="celda">Iva Retenido</td>
                                </tr>
                                @foreach($data->cuerpoDocumento as $item)
                                <tr>
                                    <td class="celda">{{ $item->numItem }}</td>
                                    <td class="celda">{{ $item->tipoDoc }}</td>
                                    <td class="celda">{{ $item->numDocumento }}</td>
                                    <td class="celda">{{ $item->fechaEmision }}</td>
                                    <td class="celda">{{ $item->descripcion }}</td>
                                    <td class="celda">{{ number_format($item->montoSujetoGrav,2) }}</td>
                                    <td class="celda">{{ number_format($item->ivaRetenido,2) }}</td>
                                </tr>
                                @endforeach
                                <!-- Resumen -->
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" style="text-align: right; padding: 3px;" class="celda">Total Sujeto a retencion:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalSujetoRetencion,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" style="text-align: right; padding: 3px;" class="celda">Total IVA Retenido:</td>
                                    <td class="celda">{{ number_format($data->resumen->totalIVAretenido,2) }}</td>
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
                                    <td>Valor en Letras IVA Retenido:</td>
                                    <td>{{ $data->resumen->totalIVAretenido }}</td>
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
                                <tr>
                                    <td style="width: 25%;">Observaciones:</td>
                                    <td colspan="3">{{ $data->extension->observaciones }}</td>
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