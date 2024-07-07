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
        Schema::create('temp_pranpcs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('no_inet')->nullable();
            $table->string('saldo')->nullable();
            $table->string('no_tlf')->nullable();
            $table->string('email')->nullable();
            $table->string('sto')->nullable();
            $table->string('umur_customer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_pranpcs');
    }
};
