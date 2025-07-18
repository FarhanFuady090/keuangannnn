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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->primary(); // BIGINT AUTO_INCREMENT
            $table->string('name', 255); // VARCHAR(255)
            $table->string('email', 255)->unique(); // VARCHAR(255) dengan unique constraint
            $table->timestamp('email_verified_at')->nullable(); // TIMESTAMP bisa NULL
            $table->string('username', 255)->unique(); // VARCHAR(255) dengan unique constraint
            $table->string('password', 255); // VARCHAR(255)

            $table->string('role'); // Menyimpan ID dari unitpendidikan
            $table->foreign('role')->references('peran_user')->on('role');

            $table->bigInteger('no_telp')->nullable(); // BIGINT bisa NULL
            $table->enum('namaUnit',  ['-', 'TK', 'SD', 'SMP', 'SMA', 'MADIN', 'TPQ', 'YA PONDOK', 'TIDAK PONDOK'])->nullable(); // Menyimpan ID dari unitpendidikan
            $table->foreign('namaUnit')->references('namaUnit')->on('unitpendidikan');
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');

            $table->string('remember_token', 100)->nullable(); // VARCHAR(100) bisa NULL
            $table->timestamps(); // created_at dan updated_at otomatis ditambahkan
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};