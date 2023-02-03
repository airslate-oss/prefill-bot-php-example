<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Requests;

use App\Services\Requests\Prefill;
use RuntimeException;
use Tests\TestCase;

class PrefillTest extends TestCase
{
    public function testShouldFailWithInvalidPayload(): void
    {
        $this->expectException(RuntimeException::class);

        $botSettings = new Prefill(['data' => [], 'included' => []]);
        $botSettings->getBotSetup();
    }
}
