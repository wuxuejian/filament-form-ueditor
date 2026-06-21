<?php

namespace Wxj\FilamentFormUeditor\Support;

/**
 *
 *  动态配置项 IDE 提示（你可以随时添加）
 * @method $this initialFrameWidth(string $value)
 * @method $this initialFrameHeight(int $value)
 * @method $this autoHeightEnabled(bool $value)
 * @method $this toolbars(array $value)
 * @method $this serverUrl(string $value)
 *
 *  /
 */
class UeditorConfig
{
    /**
     * 所有配置项存储在这里
     */
    protected array $config = [
        'initialFrameWidth'  => '100%',
        'initialFrameHeight' => 400,
        'autoHeightEnabled'  => false,
        'toolbars'           => [],
        'serverUrl'         => '/admin/ueditor',
    ];

    public function __construct()
    {
        // 从配置文件加载并覆盖默认值
        $fileConfig = config('filament-form-ueditor.options', []);

        $this->config = array_merge($this->config, $fileConfig);
    }

    public static function make(): static
    {
        return new static();
    }

    /**
     * 动态设置配置项（链式调用）
     */
    public function set(string $key, mixed $value): static
    {
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * 魔术方法：支持 $config->initialFrameHeight(500)
     */
    public function __call($name, $arguments): static
    {
        if (count($arguments) === 0) {
            throw new \InvalidArgumentException("Missing value for config [$name]");
        }

        $this->config[$name] = $arguments[0];
        return $this;
    }

    /**
     * 获取配置数组
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * 获取 JSON（用于前端）
     */
    public function json(): string
    {
        return json_encode($this->config, JSON_UNESCAPED_UNICODE);
    }
}
