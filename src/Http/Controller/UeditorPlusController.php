<?php

namespace Wxj\FilamentFormUeditor\Http\Controller;

use Awcodes\Curator\Facades\Glide;
use Awcodes\Curator\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Resources\Media\MediaResource;

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
        $file = $request->file('upfile');

        if (! $file || ! $file->isValid()) {
            return ['state' => 'File not found'];
        }

        // -----------------------------
        // 1. 类型映射
        // -----------------------------
        $dirTemplate = match ($type) {
            'image' => $this->config['imagePathFormat'],//'uploads/ueditor/image',///uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}'
            'video' => $this->config['videoPathFormat'],
            default => $this->config['filePathFormat'],
        };
        $dir = $this->generateDir($dirTemplate);
        $extension = strtolower($file->getClientOriginalExtension());

        $originalName = $file->getClientOriginalName();

        // -----------------------------
        // 2. 文件名防冲突
        // -----------------------------
        $fileName = Str::uuid()->toString() . '.' . $extension;

//        $path = $file->storeAs($dir, $fileName, [
//            'disk' => config('filesystems.default')
//        ]);

        $path = $file->store($dir);

        $diskName = config('filesystems.default');
        $disk = Storage::disk($diskName);

        $url = $disk->url($path);

        // -----------------------------
        // 3. 可选：Curator 集成
        // -----------------------------
        if (
            $this->useMedia()
        ) {
            $data = [
                'disk' => $diskName,
                'directory' => $dir,
                'visibility' => 'public',
                'name' => $fileName,
                'title' => $originalName,
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'ext' => $extension,
            ];
            if($type == 'image') {
                $manager = Glide::getServer()->getApi()->getImageManager();
                $content = $file->getRealPath();
                $image = $manager->read($content);
                $width = $image->width();
                $height = $image->height();
                $exif = $image->exif()->toArray();
                $data['width'] = $width;
                $data['height'] = $height;
                $data['exif'] = $exif;
            }
            \Awcodes\Curator\Models\Media::create($data);
        }

        // -----------------------------
        // 4. 返回 UEditor 标准格式
        // -----------------------------
        return [
            'state'    => 'SUCCESS',
            'url'      => $url,
            'title'    => $fileName,
            'original' => $originalName,
            'type'     => $file->getMimeType(),
            'size'     => $file->getSize(),
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

        if(!$this->useMedia()) {
            return [
                'state' => 'SUCCESS',
                'list'  => [],
                'start' => $start,
                'total' => 0,
            ];
        }
        $modelClass = MediaResource::getModel();
        $medias = $modelClass::query()->orderBy('id','desc')->paginate(perPage:$size,page:1);//($start, $size);
//        $dir = $type === 'image'
//            ? 'uploads/ueditor/image'
//            : 'uploads/ueditor/file';
//
//        $allFiles = Storage::disk()->files($dir);
//
//        $files = array_slice($allFiles, $start, $size);
//
//        $list = [];
//        foreach ($files as $file) {
//            $list[] = [
//                'url'   => Storage::url($file),
//                'mtime' => @filemtime(storage_path('app/public/' . $file)) ?: time(),
//            ];
//        }
        $list = $medias->each(function ($media) use ($type) {
           return  [
                'url'   => $media->url,
                'mtime' => $media->updated_at,
            ];
        });
        return [
            'state' => 'SUCCESS',
            'list'  => $list,
            'start' => $start,
            'total' => $medias->total(),
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

    protected function generateDir(string $template): string
    {
        $now = now();

        $replacements = [
            '{yyyy}' => $now->format('Y'),
            '{yy}'   => $now->format('y'),
            '{mm}'   => $now->format('m'),
            '{dd}'   => $now->format('d'),
            '{hh}'   => $now->format('H'),
            '{ii}'   => $now->format('i'),
            '{ss}'   => $now->format('s'),
            '{time}' => (string) time(),
        ];

        // 1. 替换时间变量
        $dir = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );

        // 2. rand:N
        $dir = preg_replace_callback('/\{rand:(\d+)\}/', function ($matches) {
            return $this->randomNumber((int) $matches[1]);
        }, $dir);

        // 3. 规范化斜杠（关键）
        $dir = str_replace('\\', '/', $dir);

        // 4. 只清理重复斜杠（不要 trim）
        $dir = preg_replace('#/+#', '/', $dir);

        // 5. 防止开头多余 /
        return ltrim($dir, '/');
    }

    protected function randomNumber(int $length): string
    {
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        return (string) random_int($min, $max);
    }

    private function useMedia()
    {
        if(!config('filament-form-ueditor.use_media_library')) {
            return false;
        }
        if(class_exists(\Awcodes\Curator\CuratorPlugin::class)) {
            return true;
        }
        throw new \Exception('CuratorPlugin is not installed.');
    }
}
