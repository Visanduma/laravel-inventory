<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class StockMovement extends Model
{

    use TableConfigs;

    protected $tableName = "stock_movements";
    protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }

}
