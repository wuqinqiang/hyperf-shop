<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Events\Creating;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $no
 * @property int $user_id
 * @property string $address
 * @property float $total_amount
 * @property float $pay_amount
 * @property string $user_name
 * @property string $user_phone
 * @property int $user_type
 * @property string $remark
 * @property string $paid_at
 * @property string $payment_no
 * @property int $refund_status
 * @property int $closed
 * @property int $reviewed
 * @property int $ship_status
 * @property string $ship_company
 * @property float $pay_ship
 * @property string $ship_no
 * @property string $ship_image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    const SHIP_NO=0;
    const SHIP_SUCCESS=1;
    const SHIP_GO=2;
    const REFUND_NO = 0;      //未售后
    const REFUND_SUCCESS = 1;//完成售后(建立在同意售后基础上）
    const REFUND_APPLY = 2; //申请售后中
    const REFUND_AGREE = 3; //同意售后
    const REFUND_STATUS_ERROR = 4; //拒绝售后

    const ORDER_OVER=1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['no', 'user_id', 'address', 'total_amount', 'pay_amount',
        'user_name', 'user_phone', 'user_type', 'remark', 'paid_at', 'payment_no', 'refund_status',
        'closed', 'reviewed', 'ship_status', 'ship_company', 'pay_ship', 'ship_no', 'ship_image','status'
    ];

    public function creating(Creating $event)
    {
        $this->no = self::createNo();
    }


    /**
     * @return string
     * @throws \Exception
     * 先不用考虑并发下订单重复
     */
    public static function createNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix . str_pad(random_int(0, 999999) . 'test', 6, '0', STR_PAD_LEFT);
        }
        return $no;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'user_id' => 'integer', 'total_amount' => 'float', 'pay_amount' => 'float', 'user_type' => 'integer', 'refund_status' => 'integer', 'closed' => 'integer', 'reviewed' => 'integer', 'ship_status' => 'integer', 'pay_ship' => 'float', 'created_at' => 'datetime', 'updated_at' => 'datetime','status'=>'integer'];
}