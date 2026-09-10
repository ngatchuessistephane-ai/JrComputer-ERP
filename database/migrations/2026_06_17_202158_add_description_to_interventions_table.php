<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interventions', function (Blueprint $table) {
            // Ajouter la colonne description après ticket_id
            if (!Schema::hasColumn('interventions', 'description')) {
                $table->text('description')->nullable()->after('ticket_id');
            }
        });
    }

    public function down()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};