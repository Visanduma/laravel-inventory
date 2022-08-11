<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class StockMovement extends Model
{

    use TableConfigs;

    protected $table = "stock_movements";

}
