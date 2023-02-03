<?php

namespace App\Services;

class EntitiesData
{
    public function __construct(
        private array $data
    ) {
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        $types = [];

        foreach ($this->data as $type => $entities) {
            if (is_array($entities)) {
                $types[] = (string) $type;
            }
        }

        return $types;
    }

    public function getEntities(string $type): array
    {
        return $this->data[$type];
    }
}
