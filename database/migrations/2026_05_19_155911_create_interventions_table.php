<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('sav_tickets')->onDelete('cascade');
            $table->text('technical_report');
            $table->integer('duration_minutes')->nullable();
            $table->string('client_signature')->nullable(); // chemin de l'image
            $table->boolean('synced')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interventions');
    }
};