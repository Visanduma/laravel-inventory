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
            ->hasMigration('create_laravel_inventory_tables');
    }
}
