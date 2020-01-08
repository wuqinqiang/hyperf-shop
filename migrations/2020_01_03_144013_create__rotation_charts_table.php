<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRotationChartsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("image")->comment('轮播图');
            $table->string('url')->default('#')->comment('跳转url');
            $table->unsignedInteger('status')->default(1)->comment('1开启,2禁用');
            $table->unsignedInteger('weight')->default(0)->comment('权重,越小越靠前');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_rotation_charts');
    }
}
