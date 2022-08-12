<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductCategory extends Model
{

    use TableConfigs;

    protected $tableName = "product_categories";
    protected $guarded = [];


    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
