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
        Schema::create('transaksi_tabungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabungan_id')->constrained('tabungans')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['Setoran', 'Penarikan']);
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('updated_by')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('petugas');
            $table->string('information')->nullable();
            $table->foreign('petugas')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_tabungans');
    }
};
