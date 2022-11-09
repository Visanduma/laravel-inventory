<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Product;
use Visanduma\LaravelInventory\Modals\Supplier;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_createStockForProductVariant()
    {
        $prd = $this->createProduct();
        $v = $prd->createVariant('default');

        $v->createStock(); // default batch

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

    public function test_checkProductsHelpers()
    {
        $prd = $this->createProduct();

        $v = $prd->createVariant('default');

        $v->createStock(); // default batch

        $v->stock()->add(40);
        $v->add(5); // short method

        $this->assertEquals(45, $v->stock()->qty);

        $this->assertTrue($v->inStock());
        $this->assertFalse($v->hasStock(50));
        $this->assertTrue($v->hasStock(35));
        $this->assertTrue($v->inAnyStock());

        $v->stock()->take(5);
        $v->take(5); // short method
        $this->assertEquals(35, $v->stock()->qty);

    }

}
