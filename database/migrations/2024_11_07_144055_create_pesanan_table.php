<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('pesanan_id');
            $table->string('kode_pesanan');
            $table->enum('status', ['proses', 'selesai']);
            $table->foreignId('dibuat_oleh')->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->string('channel', 50)->nullable();
            $table->date('tanggal_pesanan');
            $table->date('tanggal_pengiriman')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesanan');
    }
};
