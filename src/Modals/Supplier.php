<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\InteractWithStock;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Supplier extends Model
{

    use TableConfigs, InteractWithStock;

    protected $tableName = "suppliers";

    protected $guarded = [];


    public function address()
    {
        return $this->hasOne(Address::class, 'id');
    }


}
