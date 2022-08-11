<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Metric extends Model
{
    use TableConfigs;

    protected $table = "metrics";
}
