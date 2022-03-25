<?php

namespace ArcticSoftware\PolarLinks\Tests;

use ArcticSoftware\PolarLinks\PolarLinksServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app) {
        return [
            PolarLinksServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app) {
        // Migrate polarlink tables into in memory SQL database for testing
        include_once __DIR__ . '/../database/migrations/create_polarlinks_tables.php.stub';
        (new \CreatePolarlinksTables)->up();
    }

    public function setUp(): void {
        parent::setUp();
    }
}