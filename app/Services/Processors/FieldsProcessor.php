<?php

declare(strict_types=1);

namespace App\Services\Processors;

use App\Services\BotSettings;
use App\Services\Client\ClientFactory;
use App\Services\EntitiesDataFactory;
use App\Services\Requests\Fields;

class FieldsProcessor
{
    public function __construct(
        private ClientFactory $clientFactory,
        private EntitiesDataFactory $entitiesDataFactory,
    ) {
    }

    public function getEntityTypes(Fields $request): array
    {
        $client = $this->clientFactory->make($request->getOrganizationUid());
        $settings = new BotSettings($request->getSettings());
        $entitiesData = $this->entitiesDataFactory->load($client, $settings);
        $types = [];

        foreach ($entitiesData->getTypes() as $type) {
            $types[] = [
                'id' => $type,
                'type' => 'bot_setup_variants',
                'attributes' => [
                    'name' => $type,
                    'icon_type' => 'text',
                ],
            ];
        }

        return $types;
    }

    public function getEntityFields(Fields $request): array
    {
        $client = $this->clientFactory->make($request->getOrganizationUid());
        $botSettings = new BotSettings($request->getSettings());
        $entityType = $botSettings->getEntityType();

        $entitiesData = $this->entitiesDataFactory->load($client, $botSettings);
        $fields = [];

        foreach ($entitiesData->getEntities($entityType) as $entity) {
            foreach ($entity as $field => $value) {
                $field = (string) $field;

                $fields[] = [
                    'id' => $field,
                    'type' => 'bot_setup_variants',
                    'attributes' => [
                        'name' => $field,
                        'icon_type' => 'column',
                    ],
                ];
            }

            break;
        }

        return $fields;
    }
}
