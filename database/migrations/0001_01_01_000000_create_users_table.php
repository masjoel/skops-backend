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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('guru_id')->nullable();
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Non Aktif');
            $table->string('photo')->nullable();
            $table->string('telp')->nullable();
            $table->enum('level', [
                'administrator',
                'operator',
                'siswa',
                'guru',
                'wali kelas',
                'wali murid',
            ])->default('operator');
            $table->enum('q1', [
                'Siapa Nama lengkap Anda?',
                'Siapa Nama lengkap Ayah Anda?',
                'Siapa Nama lengkap Ibu Anda?',
                'Siapa Nama sahabat Anda?',
                'Dimana Anda dilahirkan?',
            ])->nullable();
            $table->enum('q2', [
                'Buah favorit Anda?',
                'Makanan favorit Anda?',
                'Binatang kesayangan Anda?',
            ])->nullable();

            $table->string('a1')->nullable();
            $table->string('a2')->nullable();
            $table->dateTime('jam')->nullable();
            $table->string('akses')->nullable();
            $table->char('kodeact', 4)->nullable();
            $table->rememberToken();
            $table->timestamps();
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
