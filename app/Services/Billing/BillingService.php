<?php

namespace App\Services\Billing;

use App\Services\Billing\DTO\BillingResult;
use App\Services\Billing\Support\DteRepository;
use App\Services\Billing\Support\JsonSchemaService;
use App\Services\Billing\Support\SchemaResolver;
use Illuminate\Support\Str;

class BillingService
{
    public function __construct(
        private readonly BillingExcelProcessor $excelProcessor,
        private readonly SchemaResolver $schemaResolver,
        private readonly JsonSchemaService $schemaValidator,
        //private readonly PdfService $pdfService,
        private readonly DteRepository $dteRepo
    ) {}

    /**
     * @param array{
     *   type: string,
     *   file_path: string, // storage_path('app/public/...') o ruta absoluta
     *   file_original_name: string
     *   comments?: string
     *   recintoFiscal?: string|null
     *   regimen?: string|null
     * } $payload
     */

    public function handle(array $payload): BillingResult
    {
        $type = $payload['type'];

        // 1) CSV/Excel -> JSON (string)
        $json = $this->excelProcessor->process(
            filePath: $payload['file_path'],
            type: $type,
            context: $payload,
        );

        // 2) Resolver schema + template PDF
        $schemaFile = $this->schemaResolver->schemaPathFor($type);
        $pdfTemplate = $this->schemaResolver->pdfTemplateFor($type);

        // 3) Validar contra JSON Schema (Lanza excepción si falla)
        $this->schemaValidator->validate($json, $schemaFile);

        // 4) Generar PDF (retorna filename)
        $pdfFilename = Str::uuid()->toString() . '.pdf';
        /*$this->pdfService->generateAndStore(
            json: $json,
            type: $type,
            pdfTemplate: $pdfTemplate,
            pdfFilename: $pdfFilename,
        );*/

        // 5) Ajuste especial de estructura JSON para tipos 07 y 14 (igual que hoy)
        $jsonForDb = $this->schemaResolver->normalizeJsonForStorageByType($json, $type);

        // 6) Guardar DTE en BD
        $dte = $this->dteRepo->store(
            json: $jsonForDb,
            fileCsvOriginalName: $payload['file_original_name'],
            tipoDte: $type,
        );

        // 7) Guardar JSON a disco (igual que hoy: codigoGeneracion.json)
        $jsonFilename = $this->schemaResolver->storeJsonFile($jsonForDb);

        $confirm = 'PDF Generado Satisfactoriamente para el archivo '
            . $payload['file_original_name']
            . ' Desea enviar el documento a RRD?';

        return new BillingResult(
            $dte->id,
            $confirm,
            $pdfFilename,
            $jsonFilename
        );
    }
}
