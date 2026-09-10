<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // ticket_created, ticket_closed, stock_low, etc.
            $table->string('action'); // create, update, delete, alert
            $table->string('entity_type'); // ticket, product, invoice, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('data')->nullable(); // Données supplémentaires
            $table->string('user_id')->nullable(); // ID de l'utilisateur
            $table->string('user_name')->nullable(); // Nom de l'utilisateur
            $table->string('priority')->default('normal'); // low, normal, high, critical
            $table->json('action_links')->nullable(); // Liens d'action
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'created_at']);
            $table->index(['user_id', 'is_read']);
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_activities');
    }
};