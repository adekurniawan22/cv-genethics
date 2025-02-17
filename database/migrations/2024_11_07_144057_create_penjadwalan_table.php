<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjadwalan', function (Blueprint $table) {
            $table->id('penjadwalan_id');
            $table->foreignId('pesanan_id')->constrained('pesanan', 'pesanan_id')->onDelete('cascade');
            $table->json('detail_perhitungan');  // Kolom detail_perhitungan dengan tipe JSON
            $table->timestamps();  // Menambahkan created_at dan updated_at
        });
    }


    public function down()
    {
        Schema::dropIfExists('penjadwalan');
    }
};
