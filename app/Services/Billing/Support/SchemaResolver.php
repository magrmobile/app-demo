<?php

namespace App\Services\Billing\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchemaResolver
{
    public function schemaPathFor(string $type): string
    {
        return match ($type) {
            '01' => base_path('resources/fe_schemas/fe-fc-v1.json'),
            '03' => base_path('resources/fe_schemas/fe-ccf-v3.json'),
            '04' => base_path('resources/fe_schemas/fe-nr-v3.json'),
            '05' => base_path('resources/fe_schemas/fe-nc-v3.json'),
            '06' => base_path('resources/fe_schemas/fe-nd-v3.json'),
            '07' => base_path('resources/fe_schemas/fe-cr-v1.json'),
            '11' => base_path('resources/fe_schemas/fe-fex-v1.json'),
            '14' => base_path('resources/fe_schemas/fe-fse-v1.json'),
            default => throw new \InvalidArgumentException("Tipo DTE inválido: {$type}"),
        };
    }

    public function pdfTemplateFor(string $type): string
    {
        return match ($type) {
            '01' => 'pdf.fe',
            '03' => 'pdf.ccf',
            '04' => 'pdf.nr',
            '05' => 'pdf.nc',
            '06' => 'pdf.nd',
            '07' => 'pdf.cr',
            '11' => 'pdf.fexe',
            '14' => 'pdf.fse',
            default => throw new \InvalidArgumentException("Tipo DTE inválido: {$type}"),
        };
    }

    public function normalizeJsonForStorageByType(string $json, string $type): string
    {
        if (!in_array($type, ['07', '14'], true)) {
            return $json;
        }

        $tmp = json_decode($json, true);

        if (!is_array($tmp)) {
            throw new \RuntimeException('JSON inválido al normalizar para almacenamiento');
        }

        if ($type === '07') {
            $tmp['sujetoRetencion'] = $tmp['receptor'] ?? null;
            unset($tmp['receptor']);
        }

        if ($type === '14') {
            $tmp['sujetoExcluido'] = $tmp['receptor'] ?? null;
            unset($tmp['receptor']);
        }

        return json_encode($tmp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function storeJsonFile(string $json): string
    {
        $data = json_decode($json);

        if (!$data?->identificacion?->codigoGeneracion) {
            // fallback si algo raro ocurre
            $filename = Str::uuid()->toString() . '.json';
        } else {
            $filename = $data->identificacion->codigoGeneracion . '.json';
        }

        Storage::disk('local')->put(
            'json/' . $filename,
            $json
        );

        return $filename;
    }
}