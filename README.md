# Laravel Inventory

## 🚧 UNDER DEVELOPMENT 🚧

[![Latest Version on Packagist](https://img.shields.io/packagist/v/visanduma/laravel-inventory.svg?style=flat-square)](https://packagist.org/packages/visanduma/laravel-inventory)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/visanduma/laravel-inventory/run-tests?label=tests)](https://github.com/visanduma/laravel-inventory/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/visanduma/laravel-inventory/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/visanduma/laravel-inventory/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/visanduma/laravel-inventory.svg?style=flat-square)](https://packagist.org/packages/visanduma/laravel-inventory)

Simple & reliable inventory managment package for Laravel

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

- [LaHiRu](https://github.com/Visanduma)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
