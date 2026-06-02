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
        Schema::table('orders', function (Blueprint $table) {
            // Kita tambahkan 3 "wadah" baru di sini
            $table->text('shipping_address')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('shipping_address'); // Isinya 'transfer' atau 'cod'
            $table->string('payment_proof')->nullable()->after('payment_method');   // Untuk menyimpan nama file foto struk
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jika migrasi dibatalkan, hapus 3 kolom ini
            $table->dropColumn(['shipping_address', 'payment_method', 'payment_proof']);
        });
    }
};
