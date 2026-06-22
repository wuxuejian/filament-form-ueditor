<?php

namespace Wxj\FilamentFormUeditor;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
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
            ->hasViews();
        //            ->hasAssets()
        //            ->hasMigration('create_filament_form_ueditor_table')
        //            ->hasCommand(FilamentFormUeditorCommand::class)
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            //            Css::make('filament-ckeditor-field', __DIR__ . '/../resources/dist/filament-ckeditor-field.css'),
            Js::make(
                'filament-ueditor',
                __DIR__.'/../resources/filament-ueditor.js'
            ),
            Js::make('filament-ueditor-all', __DIR__.'/../resources/dist/ueditor.all.js'),

            Js::make('filament-ueditor-config', __DIR__.'/../resources/dist/ueditor.config.js'),

        ], 'wuxuejian/filament-form-ueditor');

        // Register the render hook to inject the script into the head
        FilamentView::registerRenderHook(
            'panels::head.end',
            function (): string {
                return <<<'HTML'
                    <script>
                        // window.ckeditorInstances = window.ckeditorInstances || {};

                        // window.ue = UE.getEditor('editor', {
                        //     // ... 更多配置
                        // });

                    </script>
                HTML;
            }
        );
    }

    public function boot(): void
    {
        parent::boot();
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/dist' => public_path('js/wuxuejian/filament-form-ueditor'),
            ], 'filament-ueditor-assets');
        }
    }
}
