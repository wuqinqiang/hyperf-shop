<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use App\Exception\BusinessException;
use App\Model\FrontActivity;
use App\Model\FrontCategory;
use App\Model\FrontImage;
use App\Request\Admin\FrontCategoryRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class FrontCategoryController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class FrontCategoryController extends CommonController
{

    public function getModel()
    {
        return 'App\Model\FrontCategory';
    }

    public function mergeQuery($query,$request)
    {
        return $query;
    }

    public function isCanDelete(object $category)
    {
        if (FrontActivity::query()->where('type', $category->id)->exists()) {
            throw new BusinessException('当前类别下面有活动,不能删除', 400);
        }
        return true;
    }

    /**
     * @PostMapping(path="")
     */
    public function store(FrontCategoryRequest $request)
    {
        $category = FrontCategory::create($this->request->all());
        return $this->response->json($category);
    }

    /**
     * @param FrontCategoryRequest $request
     * @param int $id
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(FrontCategoryRequest $request, int $id)
    {
        $category = FrontCategory::find($id);
        $category->fill($request->all());
        $category->save();
        return $this->response->json($category);
    }

    /**
     * //给其他选择功能需要使用的列表
     * @return Psr7ResponseInterface
     * @GetMapping(path="front_category_list")
     */
    public function productList()
    {
        $category = FrontCategory::query()
            ->select('id', 'name')
            ->get()
            ->toArray();
        return $this->response->json(['data'=>$category]);
    }
}