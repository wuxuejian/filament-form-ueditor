<?php

namespace Wxj\FilamentFormUeditor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wxj\FilamentFormUeditor\FilamentFormUeditor
 */
class FilamentFormUeditor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Wxj\FilamentFormUeditor\FilamentFormUeditor::class;
    }
}
