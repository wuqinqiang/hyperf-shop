<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\Order;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class HomeController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class HomeController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function index()
    {
        $data = [];
        $data['order_all'] = Order::countAll()
            ->count();
        $data['order_yesterday'] = Order::countYesterday()
            ->count();
        $data['pay_amount'] = Order::countAll()
            ->sum('pay_amount');
        $data['pay_amount_yesterday'] = Order::countYesterday()
            ->sum('pay_amount');

        $data['refund_amount'] = Order::refundAmount()->sum('pay_amount');

        $data['refund_amount_yesterday'] = Order::refundAmountYesterday()->sum('pay_amount');

        return $this->response->json(['data' => $data]);

    }


    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @GetMapping(path="history")
     */
    public function history()
    {
        $data['trade'] = 10;
        $data['pay'] = 20;
        $data['refund'] = 30;
        $data['pv'] = 40;
        $data['uv'] = 50;
        $data['register'] = 60;
        $data['agent'] = 70;
        $data['buy'] = 11;
        $data['order'] = Order::query()->whereNotNull('paid_at')->count();
        $data['withdraws'] = 22;
        $data['trade_all'] = 33;
        $data['pay_all'] = 44;
        $data['refund_all'] = 55;
        $data['pv_all'] = 66;
        $data['uv_all'] = 77;
        $data['register_all'] = 22;
        $data['agent_all'] = 33;
        $data['buy_all'] = 44;
        return $this->response->json(['data' => $data]);
    }
}