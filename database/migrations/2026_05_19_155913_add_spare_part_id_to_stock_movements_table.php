<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('spare_part_id')->nullable()->after('product_id')->constrained('spare_parts');
        });
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['spare_part_id']);
            $table->dropColumn('spare_part_id');
        });
    }
};