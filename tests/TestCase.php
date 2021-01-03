<?php

namespace Kayandra\Hashidable\Tests;

use Kayandra\Hashidable\HashidableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Database\ConsoleServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array {
        return [
            ConsoleServiceProvider::class,
            HashidableServiceProvider::class,
        ];
    }
}
