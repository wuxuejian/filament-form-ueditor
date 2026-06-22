<?php

// config for Wxj/FilamentFormUeditor
return [
    'serverUrl' => '/admin/ueditor',

    'options' => [
        'serverUrl' => '/admin/ueditor',
        'initialFrameWidth' => '100%',
        'initialFrameHeight' => 400,
        'autoHeightEnabled' => false,

        'toolbars' => [
            [
                'fullscreen', 'source', '|',
                'bold', 'italic', 'underline', '|',
                'insertimage', 'link', 'unlink',
            ],
        ],
        'shortcutMenuShows' => [
            'ai' => false,
        ],
    ],

    // 是否保存时入库
    'use_media_library' => true,

    'config' => [
        // 图片上传配置
        'imageActionName' => 'uploadimage',
        'imageFieldName' => 'upfile',
        'imageMaxSize' => 10 * 1024 * 1024,
        'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
        'imageCompressEnable' => true,
        'imageCompressBorder' => 1600,
        'imageInsertAlign' => 'none',
        'imageUrlPrefix' => '',
        'imagePathFormat' => '/uploads/ueditor/image/{yyyy}{mm}{dd}/{hh}', // {time}{rand:6}

        // 涂鸦上传配置
        'scrawlActionName' => 'uploadscrawl',
        'scrawlFieldName' => 'content',
        'scrawlMaxSize' => 10 * 1024 * 1024,
        'scrawlUrlPrefix' => '',
        'scrawlInsertAlign' => 'none',
        'scrawlPathFormat' => '/uploads/ueditor/scrawl/{yyyy}{mm}{dd}/{time}{rand:6}',

        // 截图上传配置
        'snapscreenActionName' => 'uploadimage',
        'snapscreenUrlPrefix' => '',
        'snapscreenInsertAlign' => 'none',

        // 抓取远程图片配置
        'catcherActionName' => 'catchimage',
        'catcherFieldName' => 'source',
        'catcherLocalDomain' => ['127.0.0.1', 'localhost'],
        'catcherUrlPrefix' => '',
        'catcherMaxSize' => 10 * 1024 * 1024,
        'catcherAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
        'catcherPathFormat' => '/uploads/ueditor/catch/{yyyy}{mm}{dd}/{time}{rand:6}',

        // 视频上传配置
        'videoActionName' => 'uploadvideo',
        'videoFieldName' => 'upfile',
        'videoUrlPrefix' => '',
        'videoMaxSize' => 100 * 1024 * 1024,
        'videoAllowFiles' => ['.mp4', '.avi', '.rm', '.rmvb', '.wmv'],
        'videoPathFormat' => '/uploads/ueditor/video/{yyyy}{mm}{dd}/{hh}', // {time}{rand:6}

        // 文件上传配置
        'fileActionName' => 'uploadfile',
        'fileFieldName' => 'upfile',
        'fileUrlPrefix' => '',
        'fileMaxSize' => 50 * 1024 * 1024,
        'fileAllowFiles' => ['.zip', '.rar', '.7z', '.doc', '.docx', '.xls', '.xlsx', '.pdf'],
        'filePathFormat' => '/uploads/ueditor/file/{yyyy}{mm}{dd}/{hh}', // {time}{rand:6}

        // 图片列表配置
        'imageManagerActionName' => 'listimage',
        'imageManagerListSize' => 20,
        'imageManagerUrlPrefix' => '',
        'imageManagerInsertAlign' => 'none',
        'imageManagerAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],

        // 文件列表配置
        'fileManagerActionName' => 'listfile',
        'fileManagerListSize' => 20,
        'fileManagerUrlPrefix' => '',
        'fileManagerAllowFiles' => ['.zip', '.rar', '.7z', '.doc', '.docx', '.xls', '.xlsx', '.pdf'],

        // 公式配置（可选）
        'formulaConfig' => [
            'imageUrlTemplate' => 'https://r.latexeasy.com/image.svg?{}',
        ],
    ],
];
