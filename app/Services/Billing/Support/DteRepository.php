<?php

namespace App\Services\Billing\Support;

use App\Models\Customer;
use App\Models\Dte;
use Illuminate\Support\Facades\Auth;

class DteRepository
{
    public function store(string $json, string $fileCsvOriginalName, string $tipoDte): Dte
    {
        $data = json_decode($json);

        $customer = match ($tipoDte) {
            '07' => Customer::where('nit', $data->sujetoRetencion->numDocumento ?? $data->sujetoRetencion->nit)->first(),
            '14' => Customer::where('nit', $data->sujetoExcluido->numDocumento ?? $data->sujetoExcluido->nit)->first(),
            default => Customer::where('nit', $data->receptor->numDocumento ?? $data->receptor->nit)->first(),
        };

        if (!$customer) {
            throw new \RuntimeException('No se encontró Customer para guardar el DTE');
        }

        return dte::create([
            'customer_id' => $customer->id,
            'numeroControl' => $data->identificacion->numeroControl,
            'codigoGeneracion' => $data->identificacion->codigoGeneracion,
            'file_csv' => $fileCsvOriginalName,
            'json_dte' => $json,
            'created_by' => Auth::id(), // Filament lo tendrá autenticado
            'tipoDte' => $data->identificacion->tipoDte,
        ]);
    }
}
