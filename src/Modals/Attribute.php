<?php

namespace Visanduma\LaravelInventory\Modals;

use Illuminate\Database\Eloquent\Model;
use Visanduma\LaravelInventory\Traits\TableConfigs;

class Attribute extends Model
{
    use TableConfigs;

    protected $tableName = "attributes";

    protected $guarded = [];
    public $timestamps = null;


    public function product()
    {
        return $this->morphTo();
    }

}
