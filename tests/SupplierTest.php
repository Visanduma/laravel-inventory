<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Address;
use Visanduma\LaravelInventory\Modals\Supplier;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_createSupplier()
    {
        $adr = Address::create([
            'city' => 'Anuradhapura',
            'country' => 'LK'
        ]);

        $sup = Supplier::create([
            'name' => 'Supp one',
            'address_id' => $adr
        ]);


        $this->assertCount(1, Supplier::all());
        $this->assertEquals('LK', $sup->address->country);
    }
}
