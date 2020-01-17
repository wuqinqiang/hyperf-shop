<?php

namespace App\Controller\Admin;

use App\Model\Order;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class HomeController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class HomeController
{
    public function index()
    {
        $data = [];
        $data['order_all'] = Order::countAll()
            ->count();
        $data['order_yesterday']=Order::countYesterday()
            ->count();

    }
}