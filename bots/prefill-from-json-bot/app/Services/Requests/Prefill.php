<?php

declare(strict_types=1);

namespace App\Services\Requests;

class Prefill
{
    public function __construct(
        private array $payload
    ) {
    }

    public function getBotSetup(): array
    {
        return $this->getFirstIncluded('bot_setups');
    }

    public function getTemplate(): array
    {
        return $this->getFirstIncluded('templates');
    }

    public function getOrganizationUid(): string
    {
        return $this->getTemplate()['relationships']['organization']['data']['id'];
    }

    private function getFirstIncluded(string $type): array
    {
        foreach ($this->payload['included'] as $include) {
            if ($include['type'] === $type) {
                return $include;
            }
        }

        throw new \RuntimeException("Not found include with type '$type'");
    }
}
