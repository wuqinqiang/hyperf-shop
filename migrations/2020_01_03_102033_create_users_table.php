<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',20);
            $table->string('email',30)->nullable();
            $table->string('phone',20)->nullable();
            $table->unsignedTinyInteger('gender')->nullable()->comment('1 男,2 女');
            $table->unsignedTinyInteger('type')->default(2)->comment('1普通用户,2系统管理员');
            $table->string('password',199);
            $table->timestamp('deleted_at')->nullable();
            $table->decimal('monetary',10,2)->default(0)->comment('总消费金额');
            $table->string('open_id',199)->nullable()->comment('微信openid');
            $table->string('wechat_openid',199)->nullable()->comment('微信openID');
            $table->string('union_id',199)->nullable()->comment('微信union_id');
            $table->string('avatar_url',199)->nullable()->comment('头像avatar_url');
            $table->string('wechat',30)->nullable()->comment('用户微信号');
            $table->string('location',50)->nullable()->comment('用户所在地区');
            $table->unsignedInteger('buy_count')->default(0)->comment('用户购买次数');
            $table->timestamp('last_buy')->nullable()->comment('最近购买时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
