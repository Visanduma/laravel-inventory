<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Product;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_createStockForProduct()
    {
        $prd = $this->createProduct();

        $prd->createStock(); // default batch

        $this->assertCount(1, $prd->stocks);

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

        $prd->createStock(); // default batch

        $prd->stock()->add(40);

        $this->assertTrue($prd->inStock());
        $this->assertTrue($prd->inAnyStock());


//        $this->expectException(BatchNotFoundException::class);
//        $prd->inStock('non exist batch');

        $this->assertEquals(40, $prd->stock()->qty);

        $prd->stock()->take(5);
        $this->assertEquals(35, $prd->stock()->qty);

        $prd->stock()->take(35);
        $this->assertFalse($prd->inAnyStock());
        $this->assertFalse($prd->hasStock(10));

        $prd->add(40);
        $prd->take(5);
        $this->assertTrue($prd->hasStock(30));

        $this->assertEquals(35, $prd->stock()->qty);

    }

}
