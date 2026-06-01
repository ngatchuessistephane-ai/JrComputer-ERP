<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('sav_tickets')->onDelete('cascade');
            $table->foreignId('spare_part_id')->constrained('spare_parts');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_items');
    }
};