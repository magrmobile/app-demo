<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class BillingService
{
    public function process(
        UploadedFile $file,
        string $type,
        string $recintoFiscal,
        string $regimen,
        string $comments
    )
    {
        return [
            'success' => true,
            'message' => 'Documento procesado correctamente.',
        ];
    }
}
