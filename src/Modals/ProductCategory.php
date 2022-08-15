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

    public function subCategories()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function setParent($category)
    {
        // todo this must e test
        $this->parent()->associate($category);
    }
}
