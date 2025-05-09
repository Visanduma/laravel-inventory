<?php

// config for Visanduma/LaravelInventory

return [

    'table_name_prefix' => 'la',

    // Models
    'models' => [
        'product' => Visanduma\LaravelInventory\Modals\Product::class,
        'product-variant' => Visanduma\LaravelInventory\Modals\ProductVariant::class,
        'supplier' => Visanduma\LaravelInventory\Modals\Supplier::class,
        'address' => Visanduma\LaravelInventory\Modals\Address::class,
        'product-category' => Visanduma\LaravelInventory\Modals\ProductCategory::class,
        'product-option' => Visanduma\LaravelInventory\Modals\ProductOption::class,
        'product-metric' => Visanduma\LaravelInventory\Modals\Metric::class,
    ]

];
