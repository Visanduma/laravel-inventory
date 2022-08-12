<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Visanduma\LaravelInventory\Modals\ProductCategory;

class CategoryTest extends TestCase
{
    use RefreshDatabase;


    public function test_createCategory()
    {
        $this->createCategory();

        $this->assertCount(1, ProductCategory::all());
    }

    private function createCategory(array $data)
    {
        return ProductCategory::create($data);
    }

    public function test_createSubCategory()
    {
        $c = $this->createCategory(['name' => 'parent']);

        $cs = $this->createCategory([
            'name' => 'child'
        ]);

        $c->subCategories()->save($cs);

        $this->assertCount(1, $c->subCategories);
    }


}
