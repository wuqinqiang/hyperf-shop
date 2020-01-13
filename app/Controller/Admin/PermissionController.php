<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use Donjan\Permission\Models\Permission;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class RoleController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class PermissionController extends CommonController
{
    public function getModel()
    {
        return 'Donjan\Permission\Models\Permission';
    }

    public function isCanDelete(object $model)
    {
        return true;
    }

    public function mergeQuery($query, RequestInterface $request)
    {
        return $query;
    }

    /**
     * @PostMapping(path="")
     */
    public function store(RequestInterface $request, ResponseInterface $response)
    {
        $permission = Permission::create([
            'name' => $request->input('name'),
            'display_name' => $request->input('display_name'),
            'url' => $request->input('url')??'',
            'parent_id' => $request->input('parent_id') ?? 0,
            'sort' => $request->input('sort') ?? 0,
        ]);
        return $response->json(['data' => $permission]);
    }

    /**
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(RequestInterface $request, int $id, ResponseInterface $response)
    {
        $permission = Permission::findById($id);
        $permission->update($request->all());
        return $response->json(['data' => $permission]);
    }

}