<?php

declare(strict_types=1);

namespace App\Services;

class BotSettings
{
    public function __construct(
        private array $settings
    ) {
    }

    public function getMapping(): array
    {
        $mapping = [];

        foreach ($this->getSetting('mapping')['data'] as $item) {
            $documentUid = $item['right_group']['data']['value'];

            foreach ($item['mapping'] as $mappingItem) {
                $documentField = $mappingItem['right_element']['data']['value'];
                $entityField = $mappingItem['left_element']['data']['value'];

                $mapping[$documentUid][$documentField] = $entityField;
            }
        }

        return $mapping;
    }

    public function getMatching(): array
    {
        $matching = [];

        foreach ($this->getSetting('matching')['data'] as $item) {
            foreach ($item['mapping'] as $mappingItem) {
                $entityField = $mappingItem['left_element']['data']['value'];

                $matching[$entityField] = $item['right_group']['data'];
            }
        }

        return $matching;
    }

    public function getEntityType(): string
    {
        return $this->getSetting('entity_type')['data']['value'];
    }

    public function getJsonFileUid(): string
    {
        return $this->getSetting('json_file')['data']['file_id'];
    }

    private function getSetting(string $name): array
    {
        foreach ($this->settings as $setting) {
            if ($setting['name'] === $name) {
                return $setting;
            }
        }

        throw new \RuntimeException("Not found setting with name '$name'");
    }
}
