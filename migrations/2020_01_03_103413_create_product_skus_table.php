<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',500)->comment('sku标题');
            $table->unsignedInteger('product_id')->comment('对应商品id');
            $table->decimal('before_price',10,2)->nullable()->comment('商品原价');
            $table->decimal('price',10,2)->comment('商品现价');
            $table->unsignedInteger('stock')->comment('sku库存');
            $table->string('sku_image',199)->comment('sku库存');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_skus');
    }
}
