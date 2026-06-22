<?php

namespace Wxj\FilamentFormUeditor;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use Wxj\FilamentFormUeditor\Http\Controller\UeditorPlusController;

class FilamentFormUeditorPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-form-ueditor';
    }

    public function register(Panel $panel): void
    {
        $panel->authenticatedRoutes(function () {
            Route::get('ueditor', [UeditorPlusController::class, 'handle']);
            Route::post('ueditor', [UeditorPlusController::class, 'handle']);
        });
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
