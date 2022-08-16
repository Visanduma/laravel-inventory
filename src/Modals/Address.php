<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Address extends Model
{
    use TableConfigs;

    protected $tableName = "address";

    protected $guarded = [];

}
