<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Persiapan integrasi Midtrans: kolom order_id, transaction_id, payment_type;
     * metode & status diubah ke string agar bisa menyimpan nilai Midtrans.
     */
    public function up(): void
    {
        Schema::table('tb_pembayaran', function (Blueprint $table) {
            $table->string('order_id', 64)->nullable()->after('id_parkir');
            $table->string('transaction_id', 64)->nullable()->after('order_id');
            $table->string('payment_type', 32)->nullable()->after('transaction_id');
        });

        // Ubah enum ke string (MySQL) agar bisa nilai: manual, qr_scan, midtrans, dll
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE tb_pembayaran MODIFY metode VARCHAR(50) DEFAULT \'manual\'');
            DB::statement('ALTER TABLE tb_pembayaran MODIFY status VARCHAR(50) DEFAULT \'pending\'');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'transaction_id', 'payment_type']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tb_pembayaran MODIFY metode ENUM('manual', 'qr_scan') DEFAULT 'manual'");
            DB::statement("ALTER TABLE tb_pembayaran MODIFY status ENUM('pending', 'berhasil', 'gagal') DEFAULT 'pending'");
        }
    }
};
