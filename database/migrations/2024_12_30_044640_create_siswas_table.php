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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nis')->unique();
            $table->unsignedBigInteger('nisn');
            $table->string('nama')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->unsignedBigInteger('kelas_id');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']);
            $table->string('namaOrtu');
            $table->string('alamatOrtu');
            $table->string('noTelp');
            $table->string('email');
            $table->unsignedBigInteger('unitpendidikan_id')->nullable();
            $table->foreign('unitpendidikan_id')->references('id')->on('unitpendidikan')->onDelete('cascade');
            $table->unsignedBigInteger('unitpendidikan_idInformal')->nullable();
            $table->foreign('unitpendidikan_idInformal')->references('id')->on('unitpendidikan')->onDelete('cascade');
            $table->unsignedBigInteger('unitpendidikan_idPondok')->nullable();
            $table->foreign('unitpendidikan_idPondok')->references('id')->on('unitpendidikan')->onDelete('cascade');
            $table->enum('status', ['Aktif', 'Non Aktif', 'Drop Out', 'Pindah', 'Lulus']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
