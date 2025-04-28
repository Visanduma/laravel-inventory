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
        $v = $prd->createVariant();
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

        $v = $prd->createVariant();

        $stock = $v->createStock('default', $this->createSupplier()); // default batch
        $stock->update([
            'expire_at' => now()->addMonth()
        ]);

        $stock->add(40);
        $v->add(5); // short method

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

        $v->refresh();
        $this->assertEquals(35, $v->stock()->qty);
        $this->assertEquals(35, $v->totalInStock());
        $this->assertEquals(35, $v->total_stock);
    }

    public function test_reduceStock()
    {
        $prd = $this->createProduct();
        $v = $prd->createVariant();
        $stock = $v->createStock('default', $this->createSupplier());

        // Add initial stock
        $stock->add(50);
        $this->assertEquals(50, $v->stock()->qty);

        // Test Stock::reduce() method
        $stock->reduce(10, 'Testing stock reduce');
        $v->refresh();
        $this->assertEquals(40, $v->stock()->qty);

        // Test ProductVariant::reduce() method
        $v->reduce(15, 'Testing variant reduce');
        $v->refresh();
        $this->assertEquals(25, $v->stock()->qty);
        $this->assertEquals(25, $v->totalInStock());
        $this->assertEquals(25, $v->total_stock);

        // Verify stock movement was created
        $this->assertCount(3, $stock->movements);
    }
}
