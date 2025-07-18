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
        Schema::create('tabungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->string('created_by');
            $table->foreign('created_by')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('updated_by')->nullable();
            $table->foreign('updated_by')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('information')->nullable();
            $table->timestamps();
            $table->softDeletes();  // Menambahkan kolom deleted_at untuk SoftDeletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabungans', function (Blueprint $table) {
            $table->dropSoftDeletes(); // menghapus kolom deleted_at jika rollback
        });
    }
};
