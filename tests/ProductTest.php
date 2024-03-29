<?php

namespace Visanduma\LaravelInventory\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Events\InventoryStockUpdate;
use Visanduma\LaravelInventory\Listeners\StockUpdate;
use Visanduma\LaravelInventory\Modals\Address;
use Visanduma\LaravelInventory\Modals\Metric;
use Visanduma\LaravelInventory\Modals\Product;
use Visanduma\LaravelInventory\Modals\ProductCategory;
use Visanduma\LaravelInventory\Modals\Supplier;

use function PHPUnit\Framework\assertEquals;

class ProductTest extends TestCase
{
    use RefreshDatabase;


    public function test_canCreateProduct()
    {
        $this->createProduct();

        $this->assertCount(1, Product::all());
    }

    private function createProduct(array $data = ['name' => 'product one']): Product
    {
        $data = array_merge($data, [
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
        $p->setCategory($c);


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
        $p->setMetric($m);

        $this->assertEquals('Kg', $p->metric->symbol);
    }

    public function test_createProductVariant()
    {
        $p = $this->createProduct();

        $p->createVariant();

        $this->assertEquals(1, $p->variants->count());
    }

    public function test_createVariantAndAssignOptions()
    {
        $p = $this->createProduct();
        $p->createOption('color', ['blue', 'green', 'red']);
        $p->createOption('size', ['sm', 'md', 'lg']);

        $this->assertTrue($p->hasOption('color'));
        $this->assertCount(3, $p->getOption('color')->values);

        $v = $p->createVariant();
        $v->options()->sync([1, 2]);
        $this->assertCount(2, $v->options);
        $this->assertEquals('blue', $v->options->first->option->value);
    }

    public function test_createSkuCodeForProductVariant()
    {
        $p = $this->createProduct();

        $v = $p->createVariant();

        $v->assignSku('vb555');

        $this->assertEquals('vb555', $v->getSku());
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
        $v = $p->createVariant();
        $v->assignSku('RM222');

        $result = Product::findBySku('RM222');

        $this->assertEquals($p->id, $result->id);
    }

    public function test_createAttributesForProductAndVariant()
    {
        $p = $this->createProduct();

        $p->addAttribute('Brand', 'Xiaomi');

        // create multiple attr
        $p->addAttributes([
            'brand' => 'redmi',
            'color' => 'silver'
        ]);

        $this->assertCount(3, $p->attributes);

        $v = $p->createVariant();
        $v->addAttribute('Time', '4min');

        $this->assertCount(1, $v->attributes);
    }


    public function test_removeAttributeFromProduct()
    {
        $p = $this->createProduct();
        $p->addAttributes([
            'brand' => 'redmi',
            'color' => 'silver'
        ]);

        $p->removeAttribute('brand');

        $this->assertCount(1, $p->attributes);
    }

    public function test_helperMethods()
    {
        $p = $this->createProduct();

        $v = $p->createVariant();

        $v->createStock('default', $this->createSupplier());
        $v->stock()->add(100);

        $v->createStock('new', $this->createSupplier());
        $v->stock('new')->add(250);

        $this->assertEquals(350, $p->currentStock());

        $v->refresh();

        $this->assertEquals(350, $v->total_stock);


        $v->update([
            'minimum_stock' => 400
        ]);

        $this->assertTrue($v->hasCriticalStock());
    }

    public function test_createOptionsAndValues()
    {
        $p = $this->createProduct();
        $o = $p->createOption('color');
        $this->assertCount(1, $p->options);

        $o->addValue('green');
        $o->addValues(['blue', 'red']);

        $this->assertCount(3, $o->values);
    }

    public function test_createVariants()
    {
        $p = $this->createProduct();

        $v = $p->createVariant([
            'weight' => [400, 600, 1000],
            'flavor' => ['vanilla', 'chocolate']
        ]);


        $this->assertTrue($p->hasVariants()); // search using sorted name
    }

    public function test_stockEvents()
    {
        $p = $this->createProduct();
        $v = $p->createVariant();

        $v->createStock('default', $this->createSupplier());
        $v->stock()->add(100);

        $listener = app()->make(StockUpdate::class);

        $event = new InventoryStockUpdate($v, 5);

        $listener->handle($event);

        $v->refresh();

        $this->assertEquals($v->total_stock, 95);
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
}
