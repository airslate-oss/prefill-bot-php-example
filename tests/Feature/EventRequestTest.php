<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Client\AirslateClient;
use App\Services\Client\ClientFactory;
use Tests\TestCase;

class EventRequestTest extends TestCase
{
    public function testShouldUpdateFieldValuesOnDocumentPrefillEvent(): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willReturn(
                $this->loadStringFixture(__DIR__ . '/fixtures/entities/bot_setup_file.json')
            );

        $client
            ->method('getFields')
            ->with('9135BA97-1410-0000-000021F6')
            ->willReturn(
                $this->loadJsonFixture(__DIR__ . '/fixtures/entities/document_fields.json')
            );

        $client
            ->expects($this->once())
            ->method('updateFields')
            ->with(
                '9135BA97-1410-0000-000021F6',
                $this->loadJsonFixture(__DIR__ . '/fixtures/entities/updated_document_fields.json')
            );

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(200)->assertExactJson([
            'message' => 'Web-hook has been processed.',
        ]);
    }

    public function testShouldDoNothingIfEntityNotMatched(): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willReturn('{"users": []}');

        $client
            ->expects($this->never())
            ->method('updateFields');

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(200)->assertExactJson([
            'message' => 'Web-hook has been processed.',
        ]);
    }

    public function testShouldDoNothingIfEntityHasNotMappedFields(): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willReturn('{"users": [{"id": 2}]}');

        $client
            ->method('getFields')
            ->with('9135BA97-1410-0000-000021F6')
            ->willReturn(
                $this->loadJsonFixture(__DIR__ . '/fixtures/entities/document_fields.json')
            );

        $client
            ->expects($this->never())
            ->method('updateFields');

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(200)->assertExactJson([
            'message' => 'Web-hook has been processed.',
        ]);
    }

    public function testShouldIgnoreNotSupportedTriggers(): void
    {
        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $request['data']['type'] = 'unknown';
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(200)->assertExactJson([
            'message' => 'Web-hook has been processed.',
        ]);
    }

    public function testShouldFailWithBrokenBotSetupFile(): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willReturn(
                '["test": "error"]'
            );

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(422)->assertExactJson([
            'message' => 'The attached JSON file could not be read, it contains an invalid structure',
        ]);
    }

    public function testShouldFailWithInternalError(): void
    {
        $client = $this->createMock(AirslateClient::class);
        $client
            ->method('getBotSetupFileContent')
            ->with('9388F146-1000-0000-0000AE67')
            ->willThrowException(new \RuntimeException());

        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory
            ->method('make')
            ->with('B4D7ED3A-F000-0000-0000D981')
            ->willReturn($client);

        $this->app->instance(ClientFactory::class, $clientFactory);

        $request = $this->loadJsonFixture(__DIR__ . '/fixtures/requests/event_document_prefill.json');
        $response = $this->postJson('api/v1/event', $request);

        $response->assertStatus(500)->assertExactJson([
            'message' => 'Oops... Something went wrong with the bot, we are already working on a solution',
        ]);
    }
}
