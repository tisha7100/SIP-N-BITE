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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method'); // Online / COD
            $table->string('payment_status')->default('pending');
            $table->text('shipping_address');
            $table->string('phone');
            $table->decimal('distance', 8, 2);
            $table->decimal('delivery_charge', 10, 2);
            $table->decimal('gst', 10, 2);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['Under Preparation', 'Ready', 'Out for Delivery', 'Delivered', 'Cancelled'])->default('Under Preparation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
