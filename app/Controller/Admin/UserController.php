<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use App\Model\User;
use App\Request\Admin\UserResquest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class UserController extends CommonController
{
    public function getModel()
    {
        return 'App\Model\User';
    }

    /**
     * @param $query
     * @param RequestInterface $request
     * @return mixed
     * 各自的查询条件
     */
    public function mergeQuery($query, RequestInterface $request)
    {
        return $query->with('roles');
    }

    public function isCanDelete(object $model)
    {
        return true;
    }

    /**
     * 新增后台用户
     * @PostMapping(path="")
     */
    public function store(UserResquest $request, ResponseInterface $response)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
        ]);
        if ($request->input('cid')) {
            $user->assignRole($request->input('cid'));
        }
        return $response->json(['data' => $user]);
    }

    /**
     * @param UserResquest $request
     * @param ResponseInterface $response
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(UserResquest $request, int $id, ResponseInterface $response)
    {
        $user = User::find($id);
        $user->fill($request->all());
        if ($request->input('password')) {
            $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
        }
        $user->save();
        if ($request->input('cid')) {
            $user->roles()->detach();
            $user->assignRole($request->input('cid'));
        }
        return $response->json(['data' => $user]);
    }

}