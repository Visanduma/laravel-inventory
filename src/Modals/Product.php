<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    public function variants()
    {
        return $this->hasMany(config('inventory.models.product-variant'), 'product_id', 'id');
    }

    public function default()
    {
        return $this->hasOne(config('inventory.models.product-variant'))->where('is_default', true);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class,'product_id');
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
        return ProductVariant::whereHas('sku', function ($q) use ($sku) {
            $q->where('code', $sku);
        })->first();
    }

    public function findVariantByName($name)
    {
        return $this->variants()->where('options', $this->sortName($name))->first();
    }

    public function createVariant($options = [])
    {

        $v = $this->variants()->create([
            'name' => $this->name
        ]);

        foreach ($options as $key => $op) {
            $this->createOption($key, $op);
        }

        return  $v;
    }

    public function createDefaultVariant()
    {
        return $this->createVariant('default', null, true);
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

    public function hasVariant($name): bool
    {
        return $this->variants()->where('options', $this->sortName($name))->exists();
    }

    public function hasDefaultVariant()
    {
        return $this->default()->exists();
    }

    public function hasVariants(): bool
    {
        return $this->variants()->count() > 0;
    }

    public function currentStock()
    {
        return $this->variants()->withSum('stocks', 'qty')->get()->sum('stocks_sum_qty') ?? 0;
    }

    public function createOption($name, $values = null)
    {
        $op =  $this->options()->firstOrCreate([
            'name' => $name,
        ]);


        if ($values) {

            $values = array_map(function ($el) {
                return [
                    'value' => $el
                ];
            }, $values);

            $op->values()->createMany($values);
        }

        return $op;
    }

    public function hasOption($name)
    {
        return $this->options()->where('name', $name)->exists();
    }

    public function getOption($name)
    {
        return $this->options()->where('name', $name)->with('values')->first();
    }

    private function sortName($name)
    {
        $name = explode("-", $name);
        sort($name);

        return implode("-", $name);
    }
}
