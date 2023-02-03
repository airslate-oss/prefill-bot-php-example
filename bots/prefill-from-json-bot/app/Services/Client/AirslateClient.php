<?php

declare(strict_types=1);

namespace App\Services\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;

class AirslateClient
{
    public function __construct(
        private Client $client
    ) {
    }

    public function getBotSetupFileContent(string $fileUid): string
    {
        $response = $this->client->get("/v2/bot-setup-files/$fileUid/download");

        return (string) $response->getBody();
    }

    public function getFields(string $documentUid): array
    {
        $response = $this->client->get("/v2/documents/{$documentUid}/fields");

        return (array)Utils::jsonDecode((string) $response->getBody(), true);
    }

    public function updateFields(string $documentUid, array $fields): void
    {
        $this->client->patch("/v2/documents/{$documentUid}/fields", [
            'json' => [
                'data' => $fields,
            ],
            'headers' => [
                'Content-Type' => 'application/vnd.api+json',
            ],
        ]);
    }
}
