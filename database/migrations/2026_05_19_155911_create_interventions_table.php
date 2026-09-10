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
            $table->foreignId('ticket_id')->constrained('sav_tickets');
            $table->foreignId('technician_id')->constrained('users');
            $table->text('description')->nullable();
            $table->text('technical_report')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('client_signature')->nullable();
            $table->boolean('synced')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interventions');
    }
};