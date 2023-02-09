<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
        return $this->hasMany(ProductVariant::class);
    }

    public function default()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
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

    public function createVariant($name, $description = null, $default = false)
    {
        $vname = $this->name." ".$this->sortName($name);

        return  $this->variants()->create([
             'name' => $default ? $this->name : $vname,
             'options' => $default ? null : $this->sortName($name),
             'description' => $description,
             'is_default' => $default,
         ]);
    }

    public function createDefaultVariant()
    {
        return $this->createVariant('default', null, true);
    }

    public function setCategory($category)
    {
        if (! $category instanceof ProductCategory) {
            $category = ProductCategory::find($category);
        }

        $this->category()->associate($category);
    }

    public function setMetric($metric)
    {
        if (! $metric instanceof Metric) {
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
        return $this->variants()->count() > 0 ;
    }

    public function addAttribute($name, $value)
    {
        $this->addAttributes([
            $name => $value,
        ]);
    }

    // create multiple attributes a once
    public function addAttributes(array $attributes)
    {
        $attributes = array_map(function ($k, $v) {
            return [
                'name' => $k,
                'value' => $v,
            ];
        }, array_keys($attributes), array_values($attributes));

        $this->attributes()->createMany($attributes);
    }

    public function removeAttribute($name)
    {
        $this->attributes()->where('name', $name)->first()->delete();
    }

    public function currentStock()
    {
        return $this->variants()->withSum('stocks', 'qty')->get()->sum('stocks_sum_qty') ?? 0;
    }

    public function createOption($name)
    {
        return $this->options()->create([
            'name' => $name,
        ]);
    }

    public function hasOption($name)
    {
        return $this->options()->where('name', $name)->exists();
    }

    private function sortName($name)
    {
        $name = explode("-", $name);
        sort($name);

        return implode("-", $name);
    }
}
