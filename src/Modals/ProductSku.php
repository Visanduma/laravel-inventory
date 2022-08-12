<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductSku extends Model
{

    use TableConfigs;

    protected $tableName = "product_sku";
    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
