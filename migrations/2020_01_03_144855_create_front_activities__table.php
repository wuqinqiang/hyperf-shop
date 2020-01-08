<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateFrontActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id')->comment('活动跳转的商品id');
            $table->string('name')->comment('活动名称');
            $table->string('type')->comment('活动类型对应front_category 主键id');
            $table->timestamp('start_at')->nullable()->comment('开始时间');
            $table->timestamp('end_at')->nullable()->comment('结束时间');
            $table->string('url')->nullable()->comment('活动链接');
            $table->text('image')->nullable()->comment('活动图片');
            $table->tinyInteger('sort')->nullable()->comment('活动排序');
            $table->tinyInteger('status')->default(0)->comment('状态:0未开始1进行中2已结束');
            $table->tinyInteger('click')->default(0)->comment('点击数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_activities_');
    }
}
