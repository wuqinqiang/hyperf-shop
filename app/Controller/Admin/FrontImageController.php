<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use App\Model\FrontImage;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class FrontImageController
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class FrontImageController extends CommonController
{
    public function getModel()
    {
        return 'App\Model\FrontImage';
    }

    public function mergeQuery($query,$request)
    {
        return $query;
    }

    public function isCanDelete(object $model)
    {
        return true;
    }

    /**
     * @PostMapping(path="")
     */
    public function store()
    {
        $frontImage = FrontImage::create($this->request->all());
        return $this->response->json($frontImage);
    }

    /**
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(int $id)
    {
        $frontImage = FrontImage::find($id);
        $frontImage->fill($this->request->all());
        $frontImage->save();
        return $this->response->json($frontImage);
    }
}