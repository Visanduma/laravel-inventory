<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class OptionValue extends Model
{
    use TableConfigs;

    protected $tableName = "option_values";
    protected $guarded = [];
    public $timestamps = null;


    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }
}
