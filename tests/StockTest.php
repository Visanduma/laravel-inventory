<?php

namespace Visanduma\LaravelInventory\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Product;
use Visanduma\LaravelInventory\Modals\Supplier;
use Visanduma\LaravelInventory\Modals\Address;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_createStockForProductVariant()
    {
        $p = $this->createProduct();
        $supplier = $this->createSupplier();

        $p->createStock('default', $supplier); // default batch

        $this->assertCount(1, $p->stocks);
    }

    private function createProduct(array $data = []): Product
    {
        $data = array_merge($data, [
            'name' => 'product one',
            'description' => 'some description',
            'category_id' => 1,
            'metric_id' => 1
        ]);


        return Product::create($data);
    }

    private function createSupplier()
    {
        $adr = Address::create([
            'city' => 'Anuradhapura',
            'country' => 'LK'
        ]);

        return Supplier::create([
            'name' => 'Supp one',
            'address_id' => $adr
        ]);
    }
    
    public function test_checkProductsHelpers()
    {
        $p = $this->createProduct();


        $stock = $p->createStock('default', $this->createSupplier()); // default batch
        $stock->update([
            'expire_at' => now()->addMonth()
        ]);

        $stock->add(40);
        $p->add(5); // short method

        $this->assertFalse($stock->hasExpired());

        // travel to future
        $this->travelTo(now()->addMonths(2));

        $this->assertTrue($stock->hasExpired());

        $this->assertEquals(45, $p->stock()->qty);

        $this->assertTrue($p->inStock());
        $this->assertFalse($p->hasStock(50));
        $this->assertTrue($p->hasStock(35));
        $this->assertTrue($p->inAnyStock());
        $this->assertFalse($p->inAnyStock(46));

        $p->stock()->take(5);
        $p->take(5); // short method

        $p->refresh();
        $this->assertEquals(35, $p->stock()->qty);
        $this->assertEquals(35, $p->totalInStock());
    }
}
