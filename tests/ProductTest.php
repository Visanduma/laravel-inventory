<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\Metric;
use Visanduma\LaravelInventory\Modals\Product;
use Visanduma\LaravelInventory\Modals\ProductCategory;

class ProductTest extends TestCase
{
    use RefreshDatabase;


    public function test_canCreateProduct()
    {
        $this->createProduct();

        $this->assertCount(1, Product::all());
    }

    private function createProduct(array $data = [])
    {


        $data = array_merge($data, [
            'name' => 'product one',
            'description' => 'some description',
            'category_id' => 1,
            'metric_id' => 1
        ]);


        return Product::create($data);
    }

    public function test_canAssociateCategory()
    {
        $p = $this->createProduct();

        $c = ProductCategory::create([
            'name' => 'Cat one',
            'description' => 'small description'
        ]);

        $p->category()->associate($c);

        $this->assertEquals('Cat one', $p->category->name);
    }

    public function test_canAssociateMetric()
    {
        $p = $this->createProduct();

        $m = Metric::create([
            'name' => 'Kilogram',
            'symbol' => 'Kg'
        ]);

        $p->metric()->associate($m);

        $this->assertEquals('Kg', $p->metric->symbol);

    }

    public function test_createProductVariant()
    {
        $p = $this->createProduct();

        $v = $this->createProduct([
            'name' => 'Variant'
        ]);

        $v2 = $this->createProduct([
            'name' => 'More Variant'
        ]);

        $p->variants()->save($v);
        $p->refresh();

        $this->assertEquals(1, $p->variants->count());

        $p->variants()->save($v2);
        $p->refresh();

        $this->assertEquals(2, $p->variants->count());

        //check variant parent
        $this->assertEquals($p->id, $v->parent->id);

    }

    public function test_createSkuCodeForProduct()
    {
        $p = $this->createProduct();
        $p->category()->associate($this->createProductCategory());

        $p->assignSku();

        $this->assertEquals($p->generateSku(), $p->sku->code);

    }

    private function createProductCategory()
    {
        return $c = ProductCategory::create([
            'name' => 'Cat one',
            'description' => 'small description'
        ]);
    }

    public function test_findProductBySku()
    {
        $p = $this->createProduct();
        $p->category()->associate($this->createProductCategory());

        $p->assignSku();

        $this->assertEquals($p->id, Product::findBySku($p->generateSku())->id);
    }
}
