<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Client\AirslateClient;
use App\Services\Client\ClientFactory;
use Tests\TestCase;

class FieldsRequestTest extends TestCase
{
    public function testShouldReturnEntityTypes(): void
    {
        $this->mockAirslateClient(
            $this->loadStringFixture(__DIR__ . '/fixtures/entities/bot_setup_file.json')
        );

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/fields_entity_types.json');
        $response = $this->postJson('api/v1/fields', $request);
        $expectedResponse = $this->loadJsonFixture(__DIR__ . '/fixtures/responses/fields_entity_types.json');

        $response->assertStatus(200)->assertExactJson($expectedResponse);
    }

    public function testShouldReturnEmptyArrayWithEmptyFile(): void
    {
        $this->mockAirslateClient('[]');

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/fields_entity_types.json');
        $response = $this->postJson('api/v1/fields', $request);

        $response->assertStatus(200)->assertExactJson(['data' => []]);
    }

    public function testShouldReturnEntityFields(): void
    {
        $this->mockAirslateClient(
            $this->loadStringFixture(__DIR__ . '/fixtures/entities/bot_setup_file.json')
        );

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/fields_entity_fields.json');
        $response = $this->postJson('api/v1/fields', $request);
        $expectedResponse = $this->loadJsonFixture(__DIR__ . '/fixtures/responses/fields_entity_fields.json');

        $response->assertStatus(200)->assertExactJson($expectedResponse);
    }

    public function testShouldReturnEmptyArrayForEmptyEntity(): void
    {
        $this->mockAirslateClient('{"users": []}');

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/fields_entity_fields.json');
        $response = $this->postJson('api/v1/fields', $request);

        $response->assertStatus(200)->assertExactJson(['data' => []]);
    }

    public function testShouldReturnEmptyArrayForUnknownSetting(): void
    {
        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/fields_entity_fields.json');
        $request['data']['attributes']['setting_name'] = 'unknown';

        $response = $this->postJson('api/v1/fields', $request);

        $response->assertStatus(200)->assertExactJson(['data' => []]);
    }

    private function mockAirslateClient(string $botSetupFileContent): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willReturn($botSetupFileContent);

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);
    }
}
