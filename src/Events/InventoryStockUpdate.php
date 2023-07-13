<?php

namespace Visanduma\LaravelInventory\Events;

use Visanduma\LaravelInventory\Modals\ProductVariant;
use Illuminate\Queue\SerializesModels;

class InventoryStockUpdate {

   use SerializesModels;

    public function __construct(
        public ProductVariant $variant,
        public int $qty ,
        public string $batch = 'default' ,
        public ?string $reason = null ,
        public bool $increase = false
        )
    {

    }
}
