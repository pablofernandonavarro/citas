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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('patient_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->foreignId('doctor_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->dateTime('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration')->default(30);
            $table->text('reason')->nullable();
            $table->tinyInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
