<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Metric extends Model
{
    use TableConfigs;

    protected $tableName = "metrics";
    protected $guarded = [];


    public function products()
    {
        return $this->hasMany(Product::class, 'metric_id');
    }
}
