<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPaymentStatusToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, modify the status enum to include 'cancelled'
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'process', 'done', 'taken', 'cancelled') DEFAULT 'pending'");

        // Add payment_status and payment_method columns
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->default('1')->after('status');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
        
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'process', 'done', 'taken') DEFAULT 'pending'");
    }
}
