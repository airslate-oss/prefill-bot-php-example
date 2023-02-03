<?php

declare(strict_types=1);

namespace App\Services\Processors;

use App\Services\BotSettings;
use App\Services\Client\ClientFactory;
use App\Services\EntitiesDataFactory;
use App\Services\Requests\Prefill;

class PrefillProcessor
{
    public function __construct(
        private ClientFactory $clientFactory,
        private EntitiesDataFactory $entitiesDataFactory,
    ) {
    }

    public function prefill(Prefill $request): void
    {
        $settings = new BotSettings($request->getBotSetup()['attributes']['settings']);
        $client = $this->clientFactory->make($request->getOrganizationUid());

        $entities = $this
            ->entitiesDataFactory
            ->load($client, $settings)
            ->getEntities($settings->getEntityType());

        $selectedEntity = $this->findEntity($entities, $settings->getMatching());

        if ($selectedEntity === null) {
            // Nothing found, skip execution
            return;
        }

        foreach ($settings->getMapping() as $documentUid => $mappedFields) {
            $fields = $client->getFields($documentUid);
            $fieldsToUpdate = [];

            foreach ($fields['data'] as $field) {
                $fieldName = $field['attributes']['name'];
                $entityField = $mappedFields[$fieldName] ?? null;

                if ($entityField === null) {
                    // This field is not mapped,
                    // which means that we should not change its value,
                    // so just skip it
                    continue;
                }

                $newFieldValue = $selectedEntity[$entityField] ?? null;

                if ($newFieldValue === null) {
                    // This means that the JSON entity we found
                    // does not have the specified attribute,
                    // so we can only skip this field or fail the bot execution
                    continue;
                }

                $field['attributes']['value'] = $newFieldValue;
                $fieldsToUpdate[] = $field;
            }

            if ($fieldsToUpdate === []) {
                continue;
            }

            // To update fields, we simply take a field,
            // update its value attribute and send a patch request
            $client->updateFields($documentUid, $fieldsToUpdate);
        }
    }

    private function findEntity(array $entities, array $matching): ?array
    {
        foreach ($entities as $entity) {
            if ($this->isMatched($entity, $matching)) {
                return $entity;
            }
        }

        return null;
    }

    private function isMatched(array $entity, array $matching): bool
    {
        foreach ($matching as $field => $value) {
            // If the entity does not meet all the conditions,
            // then we skip it
            if ((string) $entity[$field] !== $value) {
                return false;
            }
        }

        return true;
    }
}
