<?php

declare(strict_types=1);

namespace App\Services\Client;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Contracts\Config\Repository;

class ClientFactory
{
    public function __construct(
        private Repository $config,
    ) {
    }

    public function make(string $organizationUid): AirslateClient
    {
        $response = $this
            ->makeClient($organizationUid)
            ->post('/v2/auth/token', [
                'json' => [
                    'meta' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->config->get('airslate.client_id'),
                        'client_secret' => $this->config->get('airslate.client_secret'),
                    ],
                ],
                'headers' => [
                    'Content-Type' => 'application/vnd.api+json',
                ],
            ]);

        /** @var array $body */
        $body = json_decode((string) $response->getBody(), true);

        return new AirslateClient($this->makeClient($organizationUid, $body['meta']['access_token']));
    }

    private function makeClient(string $organizationUid, string $accessToken = ''): Client
    {
        /** @var string|int $timeout */
        $timeout = $this->config->get('airslate.timeout');

        return new Client([
            'timeout' => (int)$timeout,
            'handler' => HandlerStack::create(),
            'base_uri' => $this->config->get('airslate.base_uri'),
            'headers' => [
                'Organization-ID' => $organizationUid,
                'Authorization' => "Bearer $accessToken",
            ],
        ]);
    }
}
