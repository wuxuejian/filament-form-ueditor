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

    public $defaultShortcutMenu = [
        "ai",           // AI智能
        // "fontfamily",   // 字体
        // "fontsize",     // 字号
        "bold",            // 加粗
        "italic",          // 斜体
        "underline",       // 下划线
        "strikethrough",   // 删除线
        "fontborder",      // 字符边框
        "forecolor",       // 字体颜色
        "backcolor",       // 背景色
        "imagenone",       // 图片默认
        "imageleft",       // 图片左浮动
        "imagecenter",     // 图片居中
        "imageright",      // 图片右浮动
        "insertimage",     // 插入图片
        "formula",
        // "justifyleft",    // 居左对齐
        // "justifycenter",  // 居中对齐
        // "justifyright",   // 居右对齐
        // "justifyjustify", // 两端对齐
        // "rowspacingtop",     // 段前距
        // "rowspacingbottom",  // 段后距
        // "lineheight",           // 行间距
        // "insertorderedlist",    // 有序列表
        // "insertunorderedlist",  // 无序列表
        // "superscript",    // 上标
        // "subscript",      // 下标
        // "link",           // 超链接
        // "unlink",         // 取消链接
        // "touppercase",    // 字母大写
        // "tolowercase"     // 字母小写
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



    public function set(string $key, mixed $value): static
    {
        data_set($this->config, $key, $value);

        return $this;
    }

    public function __call($name, $arguments): static
    {
        if (count($arguments) === 0) {
            throw new \InvalidArgumentException("Missing value for config [$name]");
        }

        data_set($this->config, $name, $arguments[0]);

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
