<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Stock extends Model
{

    use TableConfigs;

    protected $table = "stocks";

}
