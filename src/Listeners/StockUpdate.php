<?php

namespace Visanduma\LaravelInventory\Listeners;

use Visanduma\LaravelInventory\Events\InventoryStockUpdate;

class StockUpdate
{

    public function __construct()
    {
    }

    public function handle(InventoryStockUpdate $event)
    {
        $qty = $event->qty;
        $batch = $event->batch;
        $reason = $event->reason;

        if($event->increase){
            $event->variant->add($qty, batch: $batch, reason: $reason);
        }else{
            $event->variant->take($qty, batch: $batch, reason: $reason);
        }

    }
}
