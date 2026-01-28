<?php

namespace App\Services\Billing\DTO;

class BillingResult
{
    public function __construct(
        public int $dteId,
        public string $confirmMessage,
        public ?string $pdfFileName = null,
        public ?string $jsonFileName = null,
    ) {}
}