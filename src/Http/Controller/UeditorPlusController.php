<?php

namespace Wxj\FilamentFormUeditor\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UeditorPlusController
{

    protected array $config;

    public function handle(Request $request)
    {
        $this->config = config('filament-form-ueditor.config');
        // 只要登录 Filament 即可使用
//        $filament = app('filament');
//        if (! $filament->auth()->check()) {
//            return $this->jsonp($request, ['state' => 'Unauthorized']);
//        }

        $action = $request->input('action');

        $imageActionName = $this->config['imageActionName'];
        $catcherActionName = $this->config['catcherActionName'];
        $videoActionName = $this->config['videoActionName'];
        $fileActionName = $this->config['fileActionName'];
        $imageManagerActionName = $this->config['imageManagerActionName'];
        $fileManagerActionName = $this->config['fileManagerActionName'];
        switch ($action) {
            case 'config':
                return $this->jsonp($request, $this->config());

            case $imageActionName:
                //如果开启了入库，坚持是否安装了CuratorPlugin ,
                return $this->jsonp($request, $this->uploadFile($request, 'image'));

            case $catcherActionName:
                return $this->jsonp($request, $this->catchImage($request));

            case $videoActionName:
                return $this->jsonp($request, $this->uploadFile($request, 'video'));

            case $fileActionName:
                return $this->jsonp($request, $this->uploadFile($request, 'file'));

            case $imageManagerActionName:
                return $this->jsonp($request, $this->listFiles($request, 'image'));

            case $fileManagerActionName:
                return $this->jsonp($request, $this->listFiles($request, 'file'));

            default:
                return $this->jsonp($request, ['state' => 'Action not found']);
        }
    }

    /**
     * config：返回编辑器配置
     */
    protected function config(): array
    {
        return $this->config;
    }

    /**
     * uploadimage / uploadvideo / uploadfile
     */
    protected function uploadFile(Request $request, string $type): array
    {
        $fieldName = 'upfile';
        $file = $request->file($fieldName);

        if (! $file || ! $file->isValid()) {
            return ['state' => 'File not found'];
        }

        switch ($type) {
            case 'image':
                $dir = 'uploads/ueditor/image';
                break;
            case 'video':
                $dir = 'uploads/ueditor/video';
                break;
            default:
                $dir = 'uploads/ueditor/file';
        }

        $path = $file->store($dir );

        return [
            'state'    => 'SUCCESS',
            'url'      => Storage::url($path),
            'title'    => basename($path),
            'original' => $file->getClientOriginalName(),
        ];
    }

    /**
     * uploadscrawl：Base64 涂鸦上传
     */
    protected function uploadScrawl(Request $request): array
    {
        $base64 = $request->input('content');

        if (! $base64) {
            return ['state' => 'No content'];
        }

        $data = base64_decode($base64);
        if ($data === false) {
            return ['state' => 'Invalid base64'];
        }

        $filename = 'uploads/ueditor/scrawl/' . date('Ymd') . '/' . time() . rand(1000, 9999) . '.png';
        Storage::disk('public')->put($filename, $data);

        return [
            'state'    => 'SUCCESS',
            'url'      => Storage::url($filename),
            'title'    => basename($filename),
            'original' => 'scrawl.png',
        ];
    }

    /**
     * listimage / listfile
     */
    protected function listFiles(Request $request, string $type): array
    {
        //默认从磁盘查，支持从CuratorPlugin media 库里面查
        $start = (int) $request->input('start', 0);
        $size  = (int) $request->input('size', 20);

        $dir = $type === 'image'
            ? 'uploads/ueditor/image'
            : 'uploads/ueditor/file';

        $allFiles = Storage::disk()->files($dir);

        $files = array_slice($allFiles, $start, $size);

        $list = [];
        foreach ($files as $file) {
            $list[] = [
                'url'   => Storage::url($file),
                'mtime' => @filemtime(storage_path('app/public/' . $file)) ?: time(),
            ];
        }

        return [
            'state' => 'SUCCESS',
            'list'  => $list,
            'start' => $start,
            'total' => count($allFiles),
        ];
    }

    /**
     * catchimage：抓取远程图片
     */
    protected function catchImage(Request $request): array
    {
        $sources = $request->input('source', []);
        $list = [];

        foreach ($sources as $url) {
            try {
                $content = @file_get_contents($url);
                if ($content === false) {
                    $list[] = [
                        'state'  => 'Fetch error',
                        'url'    => '',
                        'source' => $url,
                    ];
                    continue;
                }

                //需要判断文件扩展名
                $filename = 'uploads/ueditor/catch/' . date('Ymd') . '/' . time() . rand(1000, 9999) . '.jpg';
                Storage::disk()->put($filename, $content);

                $list[] = [
                    'state'  => 'SUCCESS',
                    'url'    => Storage::url($filename),
                    'source' => $url,
                ];
            } catch (\Throwable $e) {
                $list[] = [
                    'state'  => 'Exception',
                    'url'    => '',
                    'source' => $url,
                ];
            }
        }

        return [
            'state' => 'SUCCESS',
            'list'  => $list,
        ];
    }

    /**
     * JSON / JSONP 输出
     */
    protected function jsonp(Request $request, array $data)
    {
        $callback = $request->input('callback');

        if ($callback) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            return response("$callback($json)");
        }

        return response()->json($data);
    }
}
