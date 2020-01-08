<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Controller\CommonController;
use App\Exception\BusinessException;
use App\Model\Category;
use App\Request\Admin\CategoryRequest;
use http\Exception\InvalidArgumentException;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;


/**
 * Class CategoryController
 * @package App\Controller\Admin
 * @Middleware(JwtAuthMiddleware::class)
 * @Controller()
 */
class CategoryController extends CommonController
{

    public function getModel()
    {
        return 'App\Model\Category';
    }

    public function mergeQuery($query,$request)
    {
        return $query;
    }

    public function isCanDelete(object $model)
    {
        $children = Category::where('pid', $model->id)->first();
        if ($children) {
            throw new BusinessException('当前分类有子分类,不能删除');
        }
        return true;
    }

    /**
     * @return Psr7ResponseInterface
     * @GetMapping(path="")
     */
    public function index(RequestInterface $request): Psr7ResponseInterface
    {
        $categories = Category::generateTree();
        return $this->response->json(['data' => $categories]);
    }

    /**
     * @RequestMapping(path="",methods="post")
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        return $this->response->json($category);
    }

    /**
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(CategoryRequest $request, int $id)
    {
        $category = Category::find($request->input('id'));
        $data = $request->all();
        $category->update($data);
        return $this->response->json($category);
    }


}