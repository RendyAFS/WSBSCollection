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
        Schema::create('data_masters', function (Blueprint $table) {
            $table->id();
            $table->string('event_source')->nullable();
            $table->string('kwadran')->nullable();
            $table->string('csto')->nullable();
            $table->string('mobile_contact_tel')->nullable();
            $table->string('email_address')->nullable();
            $table->string('pelanggan')->nullable();
            $table->string('alamat_pelanggan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_masters');
    }
};
