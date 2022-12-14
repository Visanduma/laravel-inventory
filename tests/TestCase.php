<?php

namespace Visanduma\LaravelInventory\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Visanduma\LaravelInventory\LaravelInventoryServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Visanduma\\LaravelInventory\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        $this->actingAs(new TestUser([
            'name' => 'Test user',
            'id' => 1
        ]));
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelInventoryServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');


        $migration = include __DIR__ . '/../database/migrations/create_laravel_inventory_tables.php';
        $migration->up();

    }
}
