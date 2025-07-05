<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Perintah ini akan mengubah kolom 'paid_at' agar boleh kosong (nullable)
        Schema::table('orders', function (Blueprint $table) {
            // ->nullable()->change() adalah kuncinya
            $table->timestamp('paid_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Jika di-rollback, kembalikan seperti semula (tidak boleh kosong)
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable(false)->change();
        });
    }
};
