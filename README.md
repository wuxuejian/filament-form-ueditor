# This is my package filament-form-ueditor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wuxuejian/filament-form-ueditor.svg?style=flat-square)](https://packagist.org/packages/wuxuejian/filament-form-ueditor)
[![GitHub Tests Action Status](https://github.com/spatie/package-filament-form-ueditor-laravel/actions/workflows/run-tests.yml/badge.svg)](https://github.com/wuxuejian/filament-form-ueditor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://github.com/spatie/package-filament-form-ueditor-laravel/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/wuxuejian/filament-form-ueditor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wuxuejian/filament-form-ueditor.svg?style=flat-square)](https://packagist.org/packages/wuxuejian/filament-form-ueditor)

Features
    Ueditor Plus integration for FilamentPHP 5 forms
    Image upload support with configurable upload URLs
    Full control over image upload handling - you implement your own upload endpoint
    Highly customizable with fluent API
    Easy to configure and use

## Support us



We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require wuxuejian/filament-form-ueditor
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-form-ueditor-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-form-ueditor-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-form-ueditor-views"
```

## Usage

```php
Ueditor::make('xxx')
    ->initialFrameWidth(400)//宽度
    ->simpleMode()// 简洁模式
    ->normalMode() //正常模式
    ->proMode() //专业模式
    ->disableAI(); //禁用AI
```

## Testing

```bash

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [wxj](https://github.com/wuxuejian)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
