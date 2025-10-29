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
        Schema::create('skors', function (Blueprint $table) {
            $table->id();
            $table->integer('urut')->nullable();
            $table->string('kode')->nullable();
            $table->integer('user_id')->nullable()->index();
            $table->text('jenis')->nullable();
            $table->double('skor')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['pelanggaran', 'reward'])->default('pelanggaran');
            $table->dateTime('jam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skors');
    }
};
