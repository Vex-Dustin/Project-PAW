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
            $table->string('resi_number')->nullable()->after('status'); // Nomor Resi
            $table->string('shipping_status')->default('Menunggu Proses')->after('resi_number'); // Status pengiriman
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['resi_number', 'shipping_status']);
        });
    }
};
