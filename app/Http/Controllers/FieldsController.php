<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Processors\FieldsProcessor;
use App\Services\Requests\Fields;
use GuzzleHttp\Utils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldsController
{
    public function __invoke(Request $request, FieldsProcessor $fieldsProcessor): JsonResponse
    {
        /** @var array $payload */
        $payload = Utils::jsonDecode((string)$request->getContent(), true);
        $fieldsRequest = new Fields($payload);

        // We have one endpoint to serve multiple resources, such as entity_types and entity_fields
        $entities = match ($fieldsRequest->getSettingName()) {
            'entity_types' => $fieldsProcessor->getEntityTypes($fieldsRequest),
            'entity_fields' => $fieldsProcessor->getEntityFields($fieldsRequest),
            default => [],
        };

        return new JsonResponse([
            'data' => $entities,
        ]);
    }
}
