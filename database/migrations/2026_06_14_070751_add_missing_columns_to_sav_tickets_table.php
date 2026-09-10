<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sav_tickets', function (Blueprint $table) {
            // Ajouter les colonnes manquantes une par une (si elles n'existent pas)
            if (!Schema::hasColumn('sav_tickets', 'technical_report')) {
                $table->text('technical_report')->nullable()->after('warranty_end_date');
            }
            
            if (!Schema::hasColumn('sav_tickets', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable()->after('technical_report');
            }
            
            if (!Schema::hasColumn('sav_tickets', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('duration_minutes');
            }
            
            if (!Schema::hasColumn('sav_tickets', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('closed_at')->constrained('users');
            }
        });
    }

    public function down()
    {
        Schema::table('sav_tickets', function (Blueprint $table) {
            $table->dropColumn(['technical_report', 'duration_minutes', 'closed_at', 'created_by']);
        });
    }
};