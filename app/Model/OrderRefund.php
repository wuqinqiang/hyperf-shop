<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $order_id 
 * @property int $refund_type 
 * @property int $refund_status 
 * @property string $refund_content 
 * @property float $refund_price 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class OrderRefund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_refunds';


    const REFUND_SUCCESS=1;
    const REFUND_HANDLE=2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','refund_type','refund_status','refund_content','refund_price'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'order_id' => 'integer',
        'refund_type' => 'integer',
        'refund_status' => 'integer', 'refund_price' => 'float',
        'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}