<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedInteger('product_id')->comment('商品id');
            $table->unsignedInteger('product_sku_id')->coment('商品sku_id');
            $table->string('product_name')->commnet('商品名称');
            $table->string('sku_title')->commnet('商品规格');
            $table->unsignedInteger('amount')->comment('商品数量');
            $table->decimal('price', 10, 2)->comment('单价');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_items');
    }
}
