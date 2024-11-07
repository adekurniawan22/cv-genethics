<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mesin', function (Blueprint $table) {
            $table->id('mesin_id');
            $table->string('nama_mesin', 100);
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->string('keterangan_mesin', 255)->nullable();
            $table->integer('kapasitas_per_hari')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mesin');
    }
};
