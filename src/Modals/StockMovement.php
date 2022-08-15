<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class StockMovement extends Model
{

    use TableConfigs;

    protected $tableName = "stock_movements";
    protected $guarded = [];

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::created(function($model){
//            $model->user_id = 5;
//        });
//    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

}
