<?php

namespace Wxj\FilamentFormUeditor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wxj\FilamentFormUeditor\Commands\FilamentFormUeditorCommand;

class FilamentFormUeditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-form-ueditor')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_filament_form_ueditor_table')
            ->hasCommand(FilamentFormUeditorCommand::class);
    }
}
