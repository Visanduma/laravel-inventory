<?php


namespace Visanduma\LaravelInventory\Traits;


use Visanduma\LaravelInventory\Modals\StockMovement;

trait InteractWithStock
{
    public function movements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
