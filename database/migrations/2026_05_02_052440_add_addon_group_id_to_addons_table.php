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
        Schema::table('addons', function (Blueprint $table) {
        if (! Schema::hasColumn('addons', 'addon_group_id')) {
            $table->foreignId('addon_group_id')
              ->nullable()
              ->after('id')
              ->constrained()
              ->cascadeOnDelete();
        }
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            if (Schema::hasColumn('addons', 'addon_group_id')) {
                $table->dropForeign(['addon_group_id']);
                $table->dropColumn('addon_group_id');
            }
        });
    }
};
