<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Stock extends Model
{

    use TableConfigs;

    protected $tableName = "stocks";
    protected $guarded = [];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'stock_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */


    public function addMovement($qty, $reason = null)
    {
        $data = [
            'before' => $this->qty,
            'after' => $this->qty + $qty,
            'user_id' => auth()->id(),
            'reason' => $reason
        ];

        $this->movements()->create($data);
    }

    public function add($qty, $reason = null)
    {
        $this->addMovement($qty, $reason);

        $this->increment('qty', $qty);
    }

    public function take($qty, $reason = null)
    {
        $this->addMovement($qty, $reason);

        $this->decrement('qty', $qty);
    }

}
