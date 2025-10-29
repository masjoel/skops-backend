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
        Schema::create('kontrols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->nullOnDelete();
            $table->foreignId('skor_id')->nullable()->constrained('skors')->nullOnDelete();
            $table->date('tgl')->nullable();
            $table->text('jenis')->nullable(); // tinytext → text
            $table->string('tipe')->nullable();
            $table->double('skor')->nullable();
            $table->text('tindakan')->nullable(); // tinytext → text
            $table->text('deskripsi')->nullable();
            $table->dateTime('jam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrols');
    }
};
