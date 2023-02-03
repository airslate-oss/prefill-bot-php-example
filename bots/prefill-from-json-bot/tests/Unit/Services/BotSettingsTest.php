<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\BotSettings;
use RuntimeException;
use Tests\TestCase;

class BotSettingsTest extends TestCase
{
    public function testShouldFailWithInvalidSettings(): void
    {
        $this->expectException(RuntimeException::class);

        $botSettings = new BotSettings([]);
        $botSettings->getEntityType();
    }
}
