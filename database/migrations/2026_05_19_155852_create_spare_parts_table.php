<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('name');
            $table->text('compatibility')->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2);
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('min_stock_alert')->default(5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spare_parts');
    }
};