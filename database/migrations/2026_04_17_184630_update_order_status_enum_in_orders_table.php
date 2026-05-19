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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Preparing', 'Under Preparation', 'Ready', 'Assigned', 'Out for Delivery', 'Delivered', 'Cancelled'])->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['Under Preparation', 'Ready', 'Out for Delivery', 'Delivered', 'Cancelled'])->default('Under Preparation')->change();
        });
    }
};
