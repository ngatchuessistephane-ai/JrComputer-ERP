<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer l'ancienne clé étrangère
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });

        // Recréer la clé avec ON DELETE CASCADE
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('invoice_id')
                  ->references('id')
                  ->on('invoices')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }
};