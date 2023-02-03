<?php

declare(strict_types=1);

namespace App\Services\Requests;

class Fields
{
    public function __construct(
        private array $payload
    ) {
    }

    public function getOrganizationUid(): string
    {
        return $this->payload['data']['relationships']['organization']['data']['id'];
    }

    public function getSettingName(): string
    {
        return $this->payload['data']['attributes']['setting_name'];
    }

    public function getSettings(): array
    {
        return $this->payload['data']['attributes']['settings'];
    }
}
