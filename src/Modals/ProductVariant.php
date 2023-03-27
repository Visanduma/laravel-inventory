<?php

namespace Visanduma\LaravelInventory\Modals;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Exceptions\BatchNotFoundException;
use Visanduma\LaravelInventory\Exceptions\SkuExistsException;
use Visanduma\LaravelInventory\Traits\HasAttributes;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductVariant extends Model
{
    use TableConfigs, HasAttributes;

    protected $tableName = "product_variants";

    protected $guarded = [];
    protected $appends = ['full_name'];

    public static function boot()
    {
        parent::boot();
        // static::updating(function ($item) {
        //     $item->name = self::sortName($item->name);
        // });
    }

    public function sku()
    {
        return $this->hasOne(ProductSku::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function options()
    {
        return $this->belongsToMany(OptionValue::class, $this->getTableName('product_variant_option_values'), 'product_variant_id', 'option_value_id');
    }

    public function getFullNameAttribute()
    {
        return $this->getFullName();
    }

    // methods

    public function assignSku($code)
    {
        try {
            $this->sku()->updateOrCreate(['product_variant_id' => $this->id], [
                'code' => $code,
            ]);
        } catch (Exception $e) {
            throw new SkuExistsException("SKU '{$code}' is already exists");
        }
    }

    public function getSku()
    {
        return $this->sku->code ?? null;
    }

    public function createStock($batch = 'default', Supplier $supplier): Stock
    {
        return $this->stocks()->create([
            'batch' => $batch,
            'qty' => 0,
            'price' => 0,
            'cost' => 0,
            'supplier_id' => $supplier->id,
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

    public function inAnyStock($qty = 0): bool
    {
        return $this->stocks()->sum('qty') > $qty;
    }

    public function hasStock($qty, $batch = 'default'): bool
    {
        return $this->stock($batch)->qty >= $qty;
    }

    public function hasCriticalStock(): bool
    {
        return !$this->inAnyStock($this->minimum_stock);
    }

    public function add($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->add($qty, $reason);
    }

    public function take($qty, $reason = null, $batch = 'default')
    {
        return $this->stock($batch)->take($qty, $reason);
    }

    public function totalInStock()
    {
        return $this->stocks()->sum('qty');
    }

    public function baseProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getFullName($separator = ' - ')
    {
        if ($this->options->isEmpty()) {
            return $this->name;
        }
        return $this->name . $separator . $this->optionString($separator);
    }

    public function optionString($separator)
    {
        return implode($separator, $this->options->pluck('value')->toArray());
    }

    public static function findBySku($sku)
    {
        return static::whereHas('sku', function ($q) use ($sku) {
            $q->where('code', $sku);
        })->first();
    }

    private static function sortName($name)
    {
        $name = explode("-", $name);
        sort($name);

        return implode("-", $name);
    }
}
