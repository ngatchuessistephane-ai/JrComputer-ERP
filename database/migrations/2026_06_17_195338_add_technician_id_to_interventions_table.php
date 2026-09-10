<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interventions', function (Blueprint $table) {
            // Ajouter la colonne technician_id si elle n'existe pas
            if (!Schema::hasColumn('interventions', 'technician_id')) {
                $table->foreignId('technician_id')->nullable()->constrained('users')->after('ticket_id');
            }
            
            // Ajouter technical_report si elle n'existe pas
            if (!Schema::hasColumn('interventions', 'technical_report')) {
                $table->text('technical_report')->nullable()->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropForeign(['technician_id']);
            $table->dropColumn('technician_id');
            $table->dropColumn('technical_report');
        });
    }
};