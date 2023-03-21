<?php

namespace Visanduma\LaravelInventory\Traits;

use Visanduma\LaravelInventory\Modals\Attribute;

trait HasAttributes {

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
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
}
