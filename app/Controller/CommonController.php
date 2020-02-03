<?php

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Paginator\Paginator;
use Hyperf\Validation\Contract\ValidatesWhenResolved;
use Hyperf\Validation\Request\FormRequest;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class CommonController
 * @package App\Controller
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
abstract class CommonController extends AbstractController
{
    //各自控制器操作的模型
    abstract function getModel();

    //是否能删除
    abstract function isCanDelete(object $model);

    /**
     * @param $query
     * @return mixed
     * 自定义查询
     */
    abstract function mergeQuery($query, RequestInterface $request);

    /**
     * @GetMapping(path="")
     */
    public function index(RequestInterface $request)
    {
        $query = $this->mergeQuery($this->getModel()::query(), $request);
        $data = $query->paginate(20)->toArray();
        return $this->ownResponse($data);
    }


    public function ownResponse($data)
    {
        return $this->response->json(['data' => $data]);
    }

    /**
     * @GetMapping(path="{id:\d+}")
     */
    public function show(int $id)
    {

    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @DeleteMapping(path="{id:\d+}")
     */
    public function destroy(int $id)
    {
        $model = $this->getModel()::find($id);
        $this->isCanDelete($model);
        $model->delete();
        return $this->response->json(['message' => '删除成功', 'code' => 200]);
    }
}