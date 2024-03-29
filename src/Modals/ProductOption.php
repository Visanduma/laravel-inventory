<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class ProductOption extends Model
{
    use TableConfigs;

    protected $tableName = "options";
    protected $guarded = [];
    public $timestamps = null;


    public function product()
    {
        return $this->belongsTo(config('inventory.models.product'), 'product_id');
    }

    public function values()
    {
        return $this->hasMany(OptionValue::class, 'option_id');
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
        foreach ($values as $v) {
            $this->addValue($v);
        }
    }

    public function removeValue($name)
    {
        $this->values()->where('value', $name)->delete();
    }

    public function valuesArray()
    {
        return $this->values->pluck('value')->toArray();
    }
}
