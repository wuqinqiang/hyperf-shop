<?php
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * Class OrderCode
 * @package App\Constants
 * @Constants
 */
class OrderCode extends AbstractConstants
{
    /**
     * 代付款
     */
    const WAIT_PAY=1;

    /**
     * 待收货
     */
    const WAIT_SHIP=2;

    /**
     * 待收货
     */
    const WAIT_EGT=3;

    /**
     * 售后
     */
    const WAIT_REFUND=4;

    /**
     * 完成订单
     */
    const ORDER_OVER=5;


}