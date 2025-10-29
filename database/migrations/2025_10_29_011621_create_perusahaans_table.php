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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->index();
            $table->string('NamaClient')->nullable();
            $table->boolean('tampil')->default(0);
            $table->string('NamaApp')->nullable();
            $table->string('VersiApp')->nullable();
            $table->string('DescApp')->nullable();
            $table->string('AlamatClient')->nullable();
            $table->string('Signature')->nullable();
            $table->string('email')->nullable();
            $table->string('Logo')->nullable();
            $table->dateTime('jam')->nullable();
            $table->string('mcad')->nullable();
            $table->string('init')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
