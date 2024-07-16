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
        Schema::create('pranpcs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('snd')->nullable();
            $table->string('alamat')->nullable();
            $table->string('bill_bln')->nullable();
            $table->string('bill_bln1')->nullable();
            $table->string('multi_kontak1')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pranpcs');
    }
};
