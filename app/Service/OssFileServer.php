<?php

namespace App\Service;

use App\Exception\BusinessException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use OSS\OssClient;

class OssFileServer
{
    public $oss;

    protected $allowType = ['png', 'jpeg', 'jpg', 'gif'];

    public function __construct()
    {
        $this->oss = new OssClient(
            config('oss.aliyun.accessKeyId'),
            config('oss.aliyun.accessKeySecret'),
            config('oss.aliyun.endpoint')
        );
        $this->oss->setTimeout(config('oss.aliyun.timeOut'));
        $this->oss->setConnectTimeout(config('oss.aliyun.connectTimeout'));
    }


    public function fileToOss(UploadedFile $file)
    {

        $path = $this->saveFile($file);
        $realName = $file->getClientFilename();
        if ($this->fileExist($realName)) {
            return $realName;
        }
        $res = $this->oss->uploadFile(
            config('oss.aliyun.bucket'),
            $file->getClientFilename(),
            config('oss.aliyun.path') . $path
        );
        return $realName;
    }


    public function fileExist($file)
    {
        return $this->oss->doesObjectExist(config('oss.aliyun.bucket'), $file) ? true : false;
    }


    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveFile(UploadedFile $file)
    {
        $extension = $file->getExtension();
        if (!in_array($extension, $this->allowType)) {
            throw new BusinessException('文件格式不正确');
        }
        $fileName = $file->getClientFilename();
        $path = config('oss.aliyun.path');
        $this->createDir($path);
        //文件保存路径
        $fileName = md5($fileName) . '.' . $file->getExtension();
        $file->moveTo($path . $fileName);
        return $fileName;
    }


    public function createDir($dir)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, 0777, true);
        }
        return true;
    }
}