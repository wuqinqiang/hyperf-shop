<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AddWeightToFrontCategory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('front_category', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(0)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('front_category', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
}
