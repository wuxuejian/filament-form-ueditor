<?php

namespace Wxj\FilamentFormUeditor\Commands;

use Illuminate\Console\Command;

class FilamentFormUeditorCommand extends Command
{
    public $signature = 'filament-form-ueditor';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
