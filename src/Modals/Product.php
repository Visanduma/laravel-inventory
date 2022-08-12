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


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function generateSku()
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

}
