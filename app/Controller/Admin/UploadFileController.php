<?php

namespace App\Controller\Admin;

use App\Service\OssFileServer;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * Class UploadFileController
 * @package App\Controller
 * @Controller()
 */
class UploadFileController
{
    /**
     * @PostMapping(path="image")
     */
    public function store(RequestInterface $request, OssFileServer $serve, ResponseInterface $response)
    {
        $file = $request->file('file');
        $res = $serve->fileToOss($file);
        $url = $serve->oss->signUrl(config('oss.aliyun.bucket'), $res, config('oss.aliyun.expire'), "GET");
        return $response->json(['path' => $url]);
    }
}