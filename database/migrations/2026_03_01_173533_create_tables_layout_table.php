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
        Schema::create('tables_layout', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique();
            $table->unsignedInteger('capacity');
            $table->integer('x_pos')->default(0);
            $table->integer('y_pos')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables_layout');
    }
};
