<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id(); // Kolom BIGINT AUTO_INCREMENT
            $table->unsignedBigInteger('unitpendidikan_id'); // Kolom BIGINT
            $table->foreign('unitpendidikan_id')->references('id')->on('unitpendidikan')->onDelete('cascade');
            $table->string('nama_kelas', 20)->unique(); // Kolom VARCHAR(20)
            $table->enum('status', ['AKTIF', 'TIDAK AKTIF'])->default('AKTIF'); // Kolom ENUM dengan default 'AKTIF'
            $table->enum('grade', ['-','A','B','C','D','E','F']);
            $table->string('keterangan', 500)->unique(); // Kolom VARCHAR(500) yang boleh NULL
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};