<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjahit', function (Blueprint $table) {
            $table->id('penjahit_id');
            $table->string('nama_penjahit', 100);
            $table->string('alamat', 255)->nullable();
            $table->string('kontak', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjahit');
    }
};
