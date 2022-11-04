<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\InteractWithStock;
use Visanduma\LaravelInventory\Traits\TableConfigs;
use Visanduma\LaravelInventory\Modals\Stock;


class Supplier extends Model
{

    use TableConfigs, InteractWithStock;

    protected $tableName = "suppliers";

    protected $guarded = [];


    public function address()
    {
        return $this->hasOne(Address::class, 'id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


}
