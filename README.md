# Laravel Inventory

## 🚧 UNDER DEVELOPMENT 🚧

[![Latest Version on Packagist](https://img.shields.io/packagist/v/visanduma/laravel-inventory.svg?style=flat-square)](https://packagist.org/packages/visanduma/laravel-inventory)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/visanduma/laravel-inventory/run-tests?label=tests)](https://github.com/visanduma/laravel-inventory/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/visanduma/laravel-inventory/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/visanduma/laravel-inventory/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/visanduma/laravel-inventory.svg?style=flat-square)](https://packagist.org/packages/visanduma/laravel-inventory)

Simple & reliable inventory management package for Laravel

## Installation

You can install the package via composer:

```bash
composer require visanduma/laravel-inventory
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="inventory-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="inventory-config"
```

This is the contents of the published config file:

```php
return [
	 'table_name_prefix' => 'la'
];
```

## Usage

### Stock Management

#### Reducing Stock

You can reduce stock in several ways:

1. Using the Stock model directly:

```php
// Get a stock instance
$stock = $productVariant->stock();

// Reduce stock by 10 units
$stock->reduce(10, 'Order #123');
```

2. Using the ProductVariant model:

```php
// Reduce stock by 5 units from the default batch
$productVariant->reduce(5, 'Order #123');

// Reduce stock from a specific batch
$productVariant->reduce(5, 'Order #123', 'batch-name');
```

3. Using the LaravelInventory facade:

```php
use Visanduma\LaravelInventory\Facades\LaravelInventory;

// Reduce stock for a product variant
LaravelInventory::reduceStock($productVariant, 10, 'Order #123');

// Reduce stock from a specific batch
LaravelInventory::reduceStock($productVariant, 10, 'Order #123', 'batch-name');
```

Each method creates a stock movement record and updates the stock quantity.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Visanduma/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Visanduma](https://github.com/Visanduma)
- [LaHiRu](https://github.com/lahirulhr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
