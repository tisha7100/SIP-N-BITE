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
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'customer'])->default('customer');
            });
        }

        // Migrate admin accounts to users table
        $admins = \Illuminate\Support\Facades\DB::table('admins')->get();
        foreach($admins as $admin) {
            $exists = \Illuminate\Support\Facades\DB::table('users')->where('email', $admin->email)->first();
            if(! $exists) {
                \Illuminate\Support\Facades\DB::table('users')->insert([
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'password' => $admin->password,
                    'role' => 'admin',
                    'login_time' => $admin->last_login_at,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at
                ]);
            } else {
                \Illuminate\Support\Facades\DB::table('users')->where('id', $exists->id)->update(['role' => 'admin']);
            }
        }

        Schema::dropIfExists('admins');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        $adminUsers = \Illuminate\Support\Facades\DB::table('users')->where('role', 'admin')->get();
        foreach($adminUsers as $admin) {
            \Illuminate\Support\Facades\DB::table('admins')->insert([
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => $admin->password,
                'last_login_at' => $admin->login_time,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
