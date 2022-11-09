<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Exceptions\BatchNotFoundException;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductVariant extends Model
{
    use TableConfigs;

    protected $tableName = "product_variants";

    protected $guarded = [];

    public function sku()
    {
        return $this->hasOne(ProductSku::class);
    }
      public function stocks()
      {
          return $this->hasMany(Stock::class);
      }

    // methods

    public function assignSku($code = null)
    {
        $this->sku()->firstOrCreate([
            'code' => $code
        ]);
    }

     public function getSku()
     {
         return $this->sku->code ?? null;
     }

    public function createStock($batch = 'default', $price = 0, $cost = 0): Stock
    {
        return $this->stocks()->create([
            'batch' => $batch,
            'qty' => 0,
            'price' => $price,
            'cost' => $cost
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

    public function inStock($batch = 'default'): bool
    {
        return $this->stock($batch)->qty > 0 ?? false;
    }

    public function inAnyStock(): bool
    {
        return $this->stocks()->sum('qty') > 0;
    }

    public function hasStock($qty, $batch = 'default'): bool
    {
        return $this->stock($batch)->qty >= $qty;
    }

    public function add($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->add($qty, $reason);
    }

    public function take($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->take($qty, $reason);
    }
}
