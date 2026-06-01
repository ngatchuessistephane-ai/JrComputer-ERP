<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();
    $table->foreignId('customer_id')->constrained()->onDelete('restrict');
    $table->date('date');
    $table->date('valid_until')->nullable();
    $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'converted'])->default('draft');
    $table->decimal('subtotal', 12, 2)->default(0);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('tax', 12, 2)->default(0);
    $table->decimal('total', 12, 2)->default(0);
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
