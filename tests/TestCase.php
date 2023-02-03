<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function loadJsonFixture(string $name): array
    {
        return (array)json_decode($this->loadStringFixture($name), true);
    }

    protected function loadStringFixture(string $path): string
    {
        return (string)file_get_contents($path);
    }
}
