<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductSku extends Model
{

    use TableConfigs;

    protected $table = "product_sku";
}
