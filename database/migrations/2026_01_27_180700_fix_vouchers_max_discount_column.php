<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixVouchersMaxDiscountColumn extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Change integer to bigInteger to allow larger values
            $table->bigInteger('max_discount')->nullable()->change();
            $table->bigInteger('min_order')->nullable()->change();
            $table->bigInteger('discount_value')->change();
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('max_discount')->nullable()->change();
            $table->integer('min_order')->nullable()->change();
            $table->integer('discount_value')->change();
        });
    }
}
