<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnapTokenToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     * (Menambahkan kolom snap_token ke tabel transactions)
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan kolom snap_token setelah kolom total_price
            // Kita set nullable() karena pesanan lama tidak punya token ini
            $table->string('snap_token')->nullable()->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     * (Menghapus kolom jika di-rollback)
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
}