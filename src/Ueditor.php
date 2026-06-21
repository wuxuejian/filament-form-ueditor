<?php

namespace Wxj\FilamentFormUeditor;



use Closure;
use Filament\Forms\Components\Field;
use Wxj\FilamentFormUeditor\Support\UeditorConfig;

class Ueditor extends Field
{
    protected string|Closure $content = '';

    protected string $name = 'ckeditor';

    protected int $minLength = 0;

    protected string|Closure|null $uploadUrl = null;

    protected bool $uploadUrlExplicitlySet = false;

    protected string $placeholder = 'Type or paste your content here...';

    protected string $view = 'filament-form-ueditor::ueditor';

    public static function make(?string $name = null): static
    {
        $field = app(static::class, [
            'name' => $name ?? 'ueditor',
        ]);

        return $field;
    }

    protected function setUp(): void
    {
        parent::setUp();

//        $this->dehydrated(false);
    }

    public function uploadUrl(string|Closure|null $uploadUrl): self
    {
        $this->uploadUrl = $uploadUrl;
        $this->uploadUrlExplicitlySet = true;

        return $this;
    }

    public function content(string|Closure $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getContent(): string
    {
        return $this->evaluate($this->content);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getUploadUrl(): ?string
    {
        if ($this->uploadUrlExplicitlySet) {
            return $this->evaluate($this->uploadUrl);
        }

        // If not explicitly set, use config value as default
        return config('filament-form-ueditor.upload_url');
    }

    public function getOptions()
    {
        $config = UeditorConfig::make();
        return $config->toArray();
//        return [
////                 'serverUrl' => config.uploadUrl,
////                 autoHeightEnabled: config.autoHeightEnabled,
////                 initialFrameHeight: config.initialFrameHeight,
//        ];
    }
}
