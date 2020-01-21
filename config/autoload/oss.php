<?php

return [
    'aliyun' => [
        'accessKeyId' => env('ACCESS_KEY'),
        'accessKeySecret' => env('ACCESS_SECRET'),
        'endpoint' => env('END_POINT'),
        'bucket' => env('BUCKET'),
        'path' => BASE_PATH . '/runtime/storage/',
        'timeOut' => 3600,
        'connectTimeout' => 10,
        'expire' => 60 * 60 * 24 * 365,
    ]
];