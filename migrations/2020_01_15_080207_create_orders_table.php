<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no')->unique()->comment('订单号');
            $table->unsignedInteger('user_id');
            $table->text('address')->comment('具体收货地址');
            $table->decimal('total_amount', 10, 2)->default(0)->comment('订单总额');
            $table->decimal('pay_amount', 10, 2)->default(0)->comment('实付金额');
            $table->string('user_name', 30)->comment('收货人姓名');
            $table->string('user_phone', 13)->comment('收货人手机号');
            $table->unsignedTinyInteger('user_type')->default(1)->comment('用户类型1普通用户2代理商');
            $table->text('remark')->nullable()->comment('订单备注');
            $table->dateTime('paid_at')->nullable()->comment('支付时间 支付时间不为空说明已支付状态');
            $table->string('payment_no')->nullable()->comment('支付订单号');
            $table->unsignedTinyInteger('refund_status')->default(0)->comment('售后状态0未申请售后1完成售后2正在申请售后3同意售后4拒绝售后');
            $table->boolean('closed')->default(false)->comment('客户确认收货订单完成关闭');
            $table->boolean('reviewed')->default(false)->comment('客户是否评价此订单');
            $table->unsignedTinyInteger('ship_status')->default(0)->comment('物流状态0未发货1已收货2已发货');
            $table->string('ship_company', 30)->nullable()->comment('物流公司');
            $table->decimal('pay_ship', 10, 2)->nullable()->comment('物流费');
            $table->string('ship_no', 50)->nullable()->comment('快递号');
            $table->string('ship_image', 199)->nullable()->comment('快递单图片');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
