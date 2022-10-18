<?php


namespace Visanduma\LaravelInventory\Modals;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Visanduma\LaravelInventory\Exceptions\BatchNotFoundException;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Product extends Model
{

    use TableConfigs;

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

    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'product_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id');
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

    public function assignSku($code = null)
    {

        $this->sku()->firstOrCreate([
            'code' => $code ?: $this->generateSku()
        ]);
    }

    public static function findBySku($sku)
    {
        return self::whereHas('sku', function ($q) use ($sku) {
            $q->where('code', $sku);
        })->first();
    }

    public function setCategory($category)
    {
        if (!$category instanceof ProductCategory) {
            $category = ProductCategory::find($category);
        }

        $this->category()->associate($category);
    }

    public function setMetric($metric)
    {
        if (!$metric instanceof Metric) {
            $metric = Metric::find($metric);
        }

        $this->metric()->associate($metric);
    }

    public function getSku()
    {
        return $this->sku->code ?? null;
    }

    public function inStock($batch = 'default'): bool
    {
        return $this->stock($batch)->qty > 0 ?? false;
    }

    public function inAnyStock(): bool
    {
        return $this->stocks()->sum('qty') > 0;
    }

    public function stock($batch = 'default'): Stock
    {
        if ($this->stocks()->where('batch', $batch)->exists()) {
            return $this->stocks()->where('batch', $batch)->first();
        } else {
            throw new BatchNotFoundException();
        }
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

    public function hasVariant($name): bool
    {
        return $this->variants()->where('name', $name)->exists();
    }

}
