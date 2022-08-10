<?php

namespace Visanduma\LaravelInventory\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Visanduma\LaravelInventory\LaravelInventory
 */
class LaravelInventory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Visanduma\LaravelInventory\LaravelInventory::class;
    }
}
