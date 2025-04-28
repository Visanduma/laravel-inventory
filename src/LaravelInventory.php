<?php

namespace Visanduma\LaravelInventory;

use Visanduma\LaravelInventory\Modals\ProductVariant;

class LaravelInventory
{
    /**
     * Reduce stock quantity for a product variant
     *
     * @param ProductVariant $variant Product variant to reduce stock for
     * @param int $qty Quantity to reduce
     * @param string|null $reason Reason for reduction
     * @param string $batch Batch name
     * @return mixed
     */
    public function reduceStock(ProductVariant $variant, int $qty, $reason = null, $batch = 'default')
    {
        return $variant->reduce($qty, $reason, $batch);
    }
}
