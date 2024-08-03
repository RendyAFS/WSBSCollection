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
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('billper_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('existing_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('pranpc_id')->nullable()->constrained()->onDelete('set null');
            $table->string('snd')->nullable();
            $table->string('witel')->nullable();
            $table->string('waktu_visit')->nullable();
            $table->foreignId('voc_kendalas_id')->nullable()->constrained()->onDelete('set null');
            $table->string('follow_up')->nullable();
            $table->string('jmlh_visit')->nullable();
            $table->string('evidence_sales')->nullable();
            $table->string('evidence_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};
