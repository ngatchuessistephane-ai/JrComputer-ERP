<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sav_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->string('serial_number')->nullable();
            $table->string('device_model')->nullable();
            $table->text('description_failure');
            $table->enum('status', ['pending', 'assigned', 'diagnosing', 'repairing', 'completed', 'restituted']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->boolean('is_warranty')->default(false);
            $table->date('warranty_end_date')->nullable();
            $table->text('technical_report')->nullable();      // ✅ AJOUTÉ
            $table->integer('duration_minutes')->nullable();   // ✅ AJOUTÉ
            $table->timestamp('closed_at')->nullable();        // ✅ AJOUTÉ
            $table->foreignId('created_by')->nullable()->constrained('users');  // ✅ AJOUTÉ
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sav_tickets');
    }
};