<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('category_id');
            $table->string('name',30);
            $table->text('description')->comment('商品详情');
            $table->unsignedTinyInteger('on_sale')->default(1)->comment('0下架1在售2定时上架');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->unsignedInteger('review_count')->default(0)->comment('评价数');
            $table->decimal('price',10,2)->comment('sku最低价');
            $table->unsignedInteger('sort')->comment('商品排序');
            $table->timestamp('time_on')->nullable()->comment('定时上架时间');
            $table->timestamp('time_off')->nullable()->comment('定时下架时间');
            $table->timestamp('deleted_at')->nullable()->comment('定时下架时间');
            $table->decimal('fare',10,2)->comment('运费');
            $table->string('front_image',199)->comment('封面图片');
            $table->text('images')->comment('商品详情图片');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
