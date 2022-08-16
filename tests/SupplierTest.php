<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Supplier;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_createSupplier()
    {
        Supplier::create([
            'name' => 'Supp one',
            'address_id' => 1
        ]);

        $this->assertCount(1, Supplier::all());
    }
}
