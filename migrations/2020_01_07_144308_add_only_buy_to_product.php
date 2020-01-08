<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AddOnlyBuyToProduct extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('only_buy')->nullable()->comment('用户最大购买次数');
            $table->unsignedInteger('only_count')->nullable()->comment('单次购买最大数量');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('only_buy');
            $table->dropColumn('only_count');
        });
    }
}
