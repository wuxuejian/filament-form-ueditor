<?php

namespace Wxj\FilamentFormUeditor;

use Closure;
use Filament\Forms\Components\Field;
use Wxj\FilamentFormUeditor\Enum\EditorMode;
use Wxj\FilamentFormUeditor\Support\UeditorConfig;

class Ueditor extends Field
{
    protected string|Closure $content = '';

    protected string $name = 'ueditor';

    protected int $minLength = 0;

    protected string|Closure|null $uploadUrl = null;

    protected bool $uploadUrlExplicitlySet = false;

    protected string $placeholder = 'Type or paste your content here...';

    protected string $view = 'filament-form-ueditor::ueditor';

    protected UeditorConfig $config;

    protected $mode = EditorMode::Simple;

    public static function make(?string $name = null): static
    {
        $field = app(static::class, [
            'name' => $name ?? 'ueditor',
        ]);
        $field->initConfig();
        $field->normalMode();

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

        return $this->config->toArray();
        //        return [
        // //                 'serverUrl' => config.uploadUrl,
        // //                 autoHeightEnabled: config.autoHeightEnabled,
        // //                 initialFrameHeight: config.initialFrameHeight,
        //        ];
    }

    public function initialFrameWidth($width): static
    {
        $this->config->initialFrameWidth($width);

        return $this;
    }

    public function initialFrameHeight($height): static
    {
        $this->config->initialFrameHeight($height);

        return $this;
    }

    public function serverUrl($url)
    {
        $this->config->serverUrl($url);

        return $this;
    }

    public function disableShortcut()
    {
        $this->config->set('shortcutMenu', []);

        return $this;
    }

    public function enableShortcutMenu()
    {
        $this->config->set('shortcutMenu', $this->config->defaultShortcutMenu);
    }

    public function disableShortcutAI(): static
    {
        $this->config->set('shortcutMenuShows.ai', false);

        return $this;
    }

    public function enableShortcutAI(): static
    {
        $this->config->set('shortcutMenuShows.ai', true);

        return $this;
    }

    public function initConfig()
    {
        $this->config = UeditorConfig::make();
    }

    public function mode(EditorMode $mode)
    {
        $this->mode = $mode;
        $this->config->set('toolbars', $mode->getToolbars());

        return $this;
    }

    public function simpleMode()
    {
        return $this->mode(EditorMode::Simple);
    }

    public function normalMode()
    {
        return $this->mode(EditorMode::Normal);
    }

    public function proMode()
    {
        return $this->mode(EditorMode::Pro);
    }

    public function disableAI()
    {
        $this->config->set('toolbarShows.ai', false);

        return $this;
    }

    public function xcall(string $method, array $parameters): mixed
    {
        // 优先 macro
        $macro = static::getMacro($method);

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);

            return $macro(...$parameters);
        }

        // 自动代理 config 方法（关键）
        if (method_exists($this->config, $method)) {
            $this->config->{$method}(...$parameters);

            return $this;
        }

        // fallback parent
        return parent::__call($method, $parameters);
    }
}
