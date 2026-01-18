<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('service_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->integer('total_price');
            $table->enum('type', ['dropoff', 'pickup_delivery'])->default('dropoff');
            $table->text('address')->nullable();
            $table->enum('status', ['pending', 'process', 'done', 'taken'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
