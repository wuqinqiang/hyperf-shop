<?php

namespace App\Controller\Admin;

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

    public function mergeQuery($query, RequestInterface $request)
    {
        return $query->with(['items'])
            ->when($no = $request->input('no'), function ($query) use ($no) {
                $query->where('no', 'like', '%' . $no . '%');
            })
            ->when($user_type = $request->user_type, function ($query) use ($user_type) {
                $query->whereUserType($user_type);
            })
            ->when($request->order_status == 1, function ($query) { //代付款
                $query->whereNull('paid_at')->whereClosed(false);
            })
            ->when($request->order_status == 2, function ($query) { //代发货
                $query->whereShipStatus(Order::SHIP_NO)->whereNotNull('paid_at')->whereRefundStatus(Order::REFUND_NO);
            })
            ->when($request->order_status == 3, function ($query) { //待收货
                $query->whereShipStatus(Order::SHIP_GO)->whereRefundStatus(Order::REFUND_NO);
            })
            ->when($request->order_status == 4, function ($query) { //皮昂嘉
                $query->where('refund_status', '>', Order::REFUND_NO);
            })
            ->when($request->order_status == 5, function ($query) { //已完成
                $query->whereStatus(true);
            });
//            ->when($request->refund_status == Order::REFUND_APPLY, function ($query) { //正在申请售后
//                $query->whereRefundStatus(Order::REFUND_APPLY);
//            });
    }

    public function isCanDelete(object $model)
    {
        return true;
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