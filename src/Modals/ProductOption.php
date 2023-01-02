<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductOption extends Model
{
    use TableConfigs;

    protected $tableName = "variants";
    protected $guarded = [];
    public $timestamps = null;


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function values()
    {
        return $this->hasMany(OptionValue::class,'variant_id');
    }


    //methods

    public function addValue(string $value)
    {
        $this->values()->create([
            'value' => trim($value)
        ]);
    }

    public function addValues(array $values)
    {
        foreach($values as $v){
            $this->addValue($v);
        }
    }

    public function valuesArray()
    {
        return $this->values->pluck('value')->toArray();
    }
}
