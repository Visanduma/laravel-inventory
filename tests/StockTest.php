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
        $prd = $this->createProduct();
        $v = $prd->createVariant('default');
        $supplier = $this->createSupplier();

        $v->createStock('default', $supplier); // default batch

        $this->assertCount(1, $v->stocks);
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
        $prd = $this->createProduct();

        $v = $prd->createVariant('default');

        $stock = $v->createStock('default', $this->createSupplier()); // default batch
        $stock->update([
            'expire_at' => now()->addMonth()
        ]);

        $stock->add(40);
        $v->add(5); // short method

        $this->assertEquals(45, $v->total_stock);

        $this->assertFalse($stock->hasExpired());

        // travel to future
        $this->travelTo(now()->addMonths(2));

        $this->assertTrue($stock->hasExpired());

        $this->assertEquals(45, $v->stock()->qty);

        $this->assertTrue($v->inStock());
        $this->assertFalse($v->hasStock(50));
        $this->assertTrue($v->hasStock(35));
        $this->assertTrue($v->inAnyStock());
        $this->assertFalse($v->inAnyStock(46));

        $v->stock()->take(5);
        $v->take(5); // short method

        $this->assertEquals(35, $v->stock()->qty);
        $this->assertEquals(35, $v->totalInStock());
    }
}
