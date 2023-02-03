<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Processors\PrefillProcessor;
use App\Services\Requests\Prefill;
use GuzzleHttp\Utils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController
{
    public function __invoke(Request $request, PrefillProcessor $prefillProcessor): JsonResponse
    {
        /** @var array $payload */
        $payload = Utils::jsonDecode((string)$request->getContent(), true);
        $eventType = $payload['data']['type'];

        match ($eventType) {
            'trigger_document_prefill' => $prefillProcessor->prefill(new Prefill($payload)),
            default => null,
        };

        return new JsonResponse([
            'message' => 'Web-hook has been processed.',
        ]);
    }
}
