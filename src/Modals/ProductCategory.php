<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductCategory extends Model
{

    use TableConfigs;

    protected $table = "product_categories";
}
