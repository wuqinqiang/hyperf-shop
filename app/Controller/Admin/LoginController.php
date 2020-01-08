<?php

namespace App\Controller\Admin;

use App\Model\User;
use App\Request\Admin\UserResquest;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Phper666\JwtAuth\Jwt;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

/**
 * Class LoginController
 * @package App\Controller\Admin
 * @Controller()
 */
class LoginController
{

    /**
     * @param RequestInterface $request
     * @PostMapping(path="register")
     */
    public function register(UserResquest $request, ResponseInterface $response): Psr7ResponseInterface
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
        $user->save();
        return $response->json($user);
    }

    /**
     * @param RequestInterface $request
     * @PostMapping(path="login")
     *
     */
    public function login(UserResquest $request, ResponseInterface $response, Jwt $jwt): Psr7ResponseInterface
    {
        $data=[];
        if (true === User::checkUser($request)) {
            $user = User::query()->where('name', $request->input('name'))->first()->toArray();
            $data['token'] = (string)$jwt->getToken(['uid' => $user['id'], 'username' => $user['name']]);
            return $response->json(['code' => 200, 'token' => $data])->withStatus(200);
        } else {
            return $response->json(['message' => '账户名或密码错误', 'code' => 400])->withStatus(405);
        }
    }
}