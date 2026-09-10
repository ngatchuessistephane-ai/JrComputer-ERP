<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('system_activities', function (Blueprint $table) {
            // Vérifier et ajouter les index uniquement s'ils n'existent pas
            if (!Schema::hasIndex('system_activities', ['is_read', 'created_at'])) {
                $table->index(['is_read', 'created_at']);
            }
            
            if (!Schema::hasIndex('system_activities', ['type', 'created_at'])) {
                $table->index(['type', 'created_at']);
            }
            
            if (!Schema::hasIndex('system_activities', ['priority', 'created_at'])) {
                $table->index(['priority', 'created_at']);
            }
            
            if (!Schema::hasIndex('system_activities', ['user_id', 'is_read'])) {
                $table->index(['user_id', 'is_read']);
            }
            
            if (!Schema::hasIndex('system_activities', ['created_at'])) {
                $table->index('created_at');
            }
        });
    }

    public function down()
    {
        Schema::table('system_activities', function (Blueprint $table) {
            // Supprimer les index uniquement s'ils existent
            if (Schema::hasIndex('system_activities', ['is_read', 'created_at'])) {
                $table->dropIndex(['is_read', 'created_at']);
            }
            
            if (Schema::hasIndex('system_activities', ['type', 'created_at'])) {
                $table->dropIndex(['type', 'created_at']);
            }
            
            if (Schema::hasIndex('system_activities', ['priority', 'created_at'])) {
                $table->dropIndex(['priority', 'created_at']);
            }
            
            if (Schema::hasIndex('system_activities', ['user_id', 'is_read'])) {
                $table->dropIndex(['user_id', 'is_read']);
            }
            
            if (Schema::hasIndex('system_activities', ['created_at'])) {
                $table->dropIndex(['created_at']);
            }
        });
    }
};