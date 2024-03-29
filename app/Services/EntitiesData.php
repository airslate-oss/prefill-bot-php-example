<?php

declare(strict_types=1);

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
            // Only an array of entities can be a type
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
