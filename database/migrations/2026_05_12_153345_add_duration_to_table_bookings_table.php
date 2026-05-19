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
    Schema::table('table_bookings', function (Blueprint $table) {
        if (! Schema::hasColumn('table_bookings', 'duration_hours')) {
            $table->integer('duration_hours')->default(1);
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('table_bookings', 'duration_hours')) {
                $table->dropColumn('duration_hours');
            }
        });
    }
};
