<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Visanduma\LaravelInventory\Exceptions\BatchNotFoundException;
use Visanduma\LaravelInventory\Traits\HasAttributes;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Product extends Model
{
    use TableConfigs, HasAttributes;

    protected $tableName = "products";

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function metric()
    {
        return $this->belongsTo(Metric::class, 'metric_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function generateSku(): string
    {
        $p1 = Str::substr(strtoupper($this->name), 0, 3);
        $p2 = Str::substr(strtoupper($this->category->name), 0, 2);
        $p3 = Str::padLeft($this->id, '4', '0');

        return "$p1-$p2$p3";
    }

    public static function findBySku($sku)
    {
        return static::where('sku',$sku)->first();
    }

    public function assignSku($sku)
    {
        return $this->update([
            'sku' => $sku
        ]);
    }

    public function createStock($batch, Supplier $supplier)
    {
        return $this->stocks()->create([
            'batch' => $batch,
            'qty' => 0,
            'supplier_id' => $supplier->id
        ]);
    }

    public function stock($batch = 'default'): Stock
    {
        if ($this->stocks()->where('batch', $batch)->exists()) {
            return $this->stocks()->where('batch', $batch)->first();
        } else {
            throw new BatchNotFoundException();
        }
    }

    public function totalInStock()
    {
        return $this->stocks()->sum('qty');
    }

    public function hasCriticalStock(): bool
    {
        return $this->totalInStock() < $this->minimum_stock;
    }

    public function add($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->add($qty, $reason);
    }

    public function take($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->take($qty, $reason);
    }

    public function inStock($batch = 'default'): bool
    {
        return $this->stock($batch)->qty > 0 ?? false;
    }

    public function inAnyStock($qty = 0): bool
    {
        return $this->stocks()->sum('qty') > $qty;
    }

    public function hasStock($qty, $batch = 'default'): bool
    {
        return $this->stock($batch)->qty >= $qty;
    }

}
