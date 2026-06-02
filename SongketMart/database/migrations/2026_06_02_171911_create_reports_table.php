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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Siapa yang mengirim laporan
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Jenis laporan (bisa Produk, Pesanan, atau Kendala Akun)
            $table->string('type');

            // Judul atau subjek laporan singkat
            $table->string('subject');

            // Detail isi laporan/keluhan
            $table->text('message');

            // Status laporan: Pending, Process, atau Resolved (Selesai)
            $table->string('status')->default('pending');

            // Opsional: Untuk mencatat bukti berupa foto jika diperlukan
            $table->string('evidence_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
