<?php

declare(strict_types=1);

namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Visanduma\LaravelInventory\Exceptions\QuantityTypeException;
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


    public function addMovement(int $qty, $reason = null)
    {
        $data = [
            'before' => $this->qty,
            'after' => $this->qty + $qty,
            'user_id' => auth()->id(),
            'reason' => $reason
        ];

       return $this->movements()->create($data);
    }

    public function add(int $qty, $reason = null)
    {
        throw_if(!is_numeric($qty), QuantityTypeException::class);

        DB::beginTransaction();

        try {

            $mov = $this->addMovement($qty, $reason);
            $this->increment('qty', $qty);

            DB::commit();

            return $mov;

        } catch (\Exception $e) {
            DB::rollBack();

            return $e;
        }

    }

    public function take(int $qty, $reason = null)
    {
        throw_if(!is_numeric($qty), QuantityTypeException::class);

        DB::beginTransaction();

        try {

            $mov = $this->addMovement($qty, $reason);
            $this->decrement('qty', $qty);

            DB::commit();

            return $mov;

        } catch (\Exception $e) {
            DB::rollBack();

            return $e;
        }
    }


}
