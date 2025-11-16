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
        Schema::create('doctor_cabinet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('cabinet_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['doctor_id', 'cabinet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_cabinet');
    }
};
