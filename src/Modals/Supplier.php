<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Supplier extends Model
{

    use TableConfigs;

    protected $table = "suppliers";

}
