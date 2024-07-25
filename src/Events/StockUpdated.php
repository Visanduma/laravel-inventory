<?php

namespace Visanduma\LaravelInventory\Events;

use Visanduma\LaravelInventory\Modals\Stock;

class StockUpdated {

    public function __construct(public Stock $stock)
    {

    }
}
