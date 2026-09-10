<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sav_tickets', function (Blueprint $table) {
            $table->decimal('labor_cost', 10, 2)->nullable()->after('duration_minutes');
            $table->decimal('diagnostic_fee', 10, 2)->default(10000)->after('labor_cost');
            $table->unsignedBigInteger('invoice_id')->nullable()->after('diagnostic_fee');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('sav_tickets', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['labor_cost', 'diagnostic_fee', 'invoice_id']);
        });
    }
};