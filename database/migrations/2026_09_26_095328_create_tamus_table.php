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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();

            $table->date('hari_tanggal');
            $table->time('jam');

            $table->string('nama');
            $table->string('instansi');

            $table->text('alamat');
            $table->text('keperluan');

            $table->string('tujuan');
            $table->string('no_handphone', 20);

            $table->text('paraf')->nullable();
            $table->string('dokumentasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
