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
            $table->integer('urutan_prioritas');
            $table->date('estimasi_selesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjadwalan');
    }
};
