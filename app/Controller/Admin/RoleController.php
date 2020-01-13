<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use Donjan\Permission\Models\Permission;
use Donjan\Permission\Models\Role;
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
class RoleController extends CommonController
{
    public function getModel()
    {
        return 'Donjan\Permission\Models\Role';
    }

    public function isCanDelete(object $model)
    {
        return true;
    }

    public function mergeQuery($query, RequestInterface $request)
    {
        return $query->with(['permissions'=>function($query){
            $query->select('id','name','display_name');
        }]);
    }

    /**
     * @PostMapping(path="")
     */
    public function store(RequestInterface $request, ResponseInterface $response)
    {
        $role = Role::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'
            )]);
        $role->givePermissionTo($request->input('permissions'));
        return $response->json(['data' => $role]);
    }

    /**
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(RequestInterface $request, int $id, ResponseInterface $response)
    {
        $role = Role::query()->find($id);
        $role->fill($request->all());
        if ($request->has('permissions')) {
            $role->permissions()->detach();
            $role->givePermissionTo($request->input('permissions'));
        }
        return $response->json(['data' => $role]);
    }

}