<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('social_work_id')
            ->nullable()
            ->constrained()
            ->onDelete('set null');
            $table->string('allergies')->nullable();
            $table->string('medical_record_number')
            ->nullable()
            ->unique();
            $table->string('chronic_conditions')->nullable();
            $table->string('surgeries_history')->nullable();
            $table->string('family_history')->nullable();
            $table->string('genetic_conditions')->nullable();
            $table->string('other_conditions')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->date('date_of_birth')->nullable();
        

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
