<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Modifier les colonnes pour accepter NULL
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('spare_part_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreignId('spare_part_id')->nullable(false)->change();
        });
    }
};