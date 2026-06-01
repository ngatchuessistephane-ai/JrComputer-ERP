<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->renameColumn('issue_date', 'date');
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->renameColumn('date', 'issue_date');
    });
}
};
