<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedTinyInteger('refund_type')->comment('1退款2退款退货3退货换货');
            $table->unsignedTinyInteger('refund_status')->default(2)->comment('1售后成功2正在售后');
            $table->text('refund_content')->comment('售后说明');
            $table->decimal('refund_price', 10, 2)->default(0)->comment('售后金额');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refunds');
    }
}
