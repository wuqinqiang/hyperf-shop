<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\Order;
use Carbon\Carbon;
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
        $data=[];
        for ($i=1;$i<10;$i++){
            $data[]=['date'=>Carbon::parse("+{$i} days")->toDateString(),'order'=>rand(4,300),'pay'=>rand(234,10000),'refund'=>rand(3,100),'pv'=>rand(10000,6666),'uv'=>rand(1000,10000),'register'=>rand(20,100),'agent'=>rand(23,1000),'buy'=>rand(100,999)];
        }
        return $this->response->json(['data' => $data]);
    }
}