<?php

declare(strict_types=1);

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Visanduma\LaravelInventory\Exceptions\QuantityTypeException;
use Visanduma\LaravelInventory\Traits\TableConfigs;
use Visanduma\LaravelInventory\Modals\Supplier;

class Stock extends Model
{
    use TableConfigs;

    protected $tableName = "stocks";
    protected $guarded = [];
    protected $dates = ['expire_at'];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */


    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }


    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'stock_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
            'qty' => $this->qty,
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
            $this->product()->increment('total_stock', $qty);

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
            $mov = $this->addMovement(-$qty, $reason);
            $this->decrement('qty', $qty);
            $this->product()->decrement('total_stock', $qty);

            DB::commit();

            return $mov;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function hasExpired(): bool
    {
        return $this->expire_at->isPast();
    }
}
