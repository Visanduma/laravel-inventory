<?php

namespace Visanduma\LaravelInventory;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Visanduma\LaravelInventory\Commands\LaravelInventoryCommand;

class LaravelInventoryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-inventory')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-inventory_table')
            ->hasCommand(LaravelInventoryCommand::class);
    }
}
