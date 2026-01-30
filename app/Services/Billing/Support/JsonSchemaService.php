<?php

namespace App\Services\Billing\Support;

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    Errors\ErrorFormatter
};

class JsonSchemaService
{
    public function validate(string $json, string $schemaFilePath): void
    {
        $schema = json_decode(file_get_contents($schemaFilePath));
        $jsonDecode = json_decode($json);

        //dd($schema->properties);

        $validator = new Validator();

        /** @var ValidationResult $result */
        $result = $validator->validate($jsonDecode, $schema->properties);

        if ($result->isValid()) {
            return;
        }

        throw new \RuntimeException($result->error());
    }
}