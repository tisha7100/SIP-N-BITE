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
        Schema::table('addon_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('addon_groups', 'name')) {
                $table->string('name')->nullable();
            }

            if (! Schema::hasColumn('addon_groups', 'type')) {
                $table->enum('type', ['radio', 'checkbox'])->default('checkbox');
            }

            if (! Schema::hasColumn('addon_groups', 'is_required')) {
                $table->boolean('is_required')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addon_groups', function (Blueprint $table) {
            if (Schema::hasColumn('addon_groups', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('addon_groups', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('addon_groups', 'is_required')) {
                $table->dropColumn('is_required');
            }
        });
    }
};
