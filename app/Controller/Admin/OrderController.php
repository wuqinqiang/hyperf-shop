<?php

namespace App\Controller\Admin;

use App\Constants\OrderCode;
use App\Controller\CommonController;
use App\Exception\BusinessException;
use App\Model\Order;
use App\Model\ProductSkus;
use App\Request\OrderRequest;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class OrderController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class OrderController extends CommonController
{

    public function getModel()
    {
        return 'App\Model\Order';
    }

    /**
     * @param $query
     * @param RequestInterface $request
     * @return mixed
     */
    public function mergeQuery($query, RequestInterface $request)
    {
        return $query->with(['items'])
            ->when($no = $request->input('no'), function ($query) use ($no) {
                $query->where('no', 'like', '%' . $no . '%');
            })
            ->when($created_at = $request->input('created_at'), function ($query) use ($created_at) {
                $query->where('created_at', '>', $created_at);
            })
            ->when($user_phone = $request->input('user_phone'), function ($query) use ($user_phone) {
                $query->where('user_phone', 'like', '%' . $user_phone . '%');
            })
            ->when($user_name = $request->input('user_name'), function ($query) use ($user_name) {
                $query->where('user_name', 'like', '%' . $user_name . '%');
            })
            ->when($request->input('order_status') == OrderCode::WAIT_PAY, function ($query) { //代付款
                $query->whereNull('paid_at')->whereClosed(false);
            })
            ->when($request->input('order_status') == OrderCode::WAIT_SHIP, function ($query) { //代发货
                $query->whereShipStatus(Order::SHIP_NO)->whereNotNull('paid_at')->whereRefundStatus(Order::REFUND_NO);
            })
            ->when($request->input('order_status') == OrderCode::WAIT_EGT, function ($query) { //待收货
                $query->whereShipStatus(Order::SHIP_GO)->whereRefundStatus(Order::REFUND_NO);
            })
            ->when($request->input('order_status') == OrderCode::WAIT_REFUND, function ($query) { //售后
                $query->where('refund_status', '>', Order::REFUND_NO);
            })
            ->when($request->input('order_status') == OrderCode::ORDER_OVER, function ($query) { //已完成
                $query->whereStatus(Order::ORDER_OVER);
            })
            ->when($refund_status = $request->input('refund_status'), function ($query) use ($refund_status) { //已完成
                $query->whereRefundStatus($refund_status);
            });
    }

    public function isCanDelete(object $model)
    {
        return true;
    }


    /**
     * @GetMapping(path="{id:\d+}")
     */
    public function show(int $id)
    {
        $order = Order::find($id);
        return $this->response->json(['data' => $order->load('items')]);
    }


    /**
     * @PostMapping(path="")
     */
    public function store(OrderRequest $request, Jwt $jwt)
    {
        $order = new Order();
        $res = Db::transaction(function () use ($order, $request) {
            $order->fill($request->all());
            $order->save();
            $total_amount = 0;
            collect($request->input('items'))->map(function ($item) use ($order, &$total_amount) {
                $sku = ProductSkus::query()->find($item['sku_id']);
                $orderItem = $order->items()->make([
                    'product_name' => $sku->product->name,
                    'sku_title' => $sku->title,
                    'amount' => $item['amount'],
                    'price' => $sku->price * $item['amount'],
                ]);
                $orderItem->product()->associate($sku->product_id);
                $orderItem->productSku()->associate($sku);
                $orderItem->save();
                $total_amount += $orderItem->price;
            });
            $order->update([
                'pay_amount' => $total_amount,
                'total_amount' => $total_amount,
            ]);
            return $order;
        });
        return $this->response->json(['data' => $res]);
    }

    /**
     * @PostMapping(path="{id:\d+}")
     */
    public function goShip(RequestInterface $request, int $id)
    {
        $order = Order::find($id);
        if (!$order->paid_at) {
            throw new BusinessException('该订单尚未付款');
        }
        if ($order->ship_status > 0) {
            throw new BusinessException('商品已发货');
        }

        $order->update([
            'ship_status' => Order::SHIP_GO,
        ]);
        return $this->response->json(['data' => $order]);
    }

}