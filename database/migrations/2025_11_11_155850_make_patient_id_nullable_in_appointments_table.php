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
        Schema::table('appointments', function (Blueprint $table) {
            // Eliminar la foreign key constraint primero
            $table->dropForeign(['patient_id']);
            
            // Modificar la columna para permitir NULL
            $table->foreignId('patient_id')
                  ->nullable()
                  ->change();
            
            // Volver a agregar la foreign key
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Eliminar la foreign key
            $table->dropForeign(['patient_id']);
            
            // Revertir a NOT NULL
            $table->foreignId('patient_id')
                  ->nullable(false)
                  ->change();
            
            // Volver a agregar la foreign key
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
        });
    }
};
