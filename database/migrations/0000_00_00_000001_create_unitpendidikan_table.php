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
        Schema::create('unitpendidikan', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['-','formal','Informal','Pondok']);
            $table->enum('namaUnit',['-','TK','SD','SMP','SMA','MADIN','TPQ','YA PONDOK','TIDAK PONDOK'])->unique();
            $table->enum('status', ['Aktif','Tidak Aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unitpendidikan');
    }
};
