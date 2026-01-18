<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar')->after('status');
            $table->unsignedBigInteger('id_pembayaran')->nullable()->after('status_pembayaran');
            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('tb_pembayaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->dropForeign(['id_pembayaran']);
            $table->dropColumn('id_pembayaran');
            $table->dropColumn('status_pembayaran');
        });
    }
};
