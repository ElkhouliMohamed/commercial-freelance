<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Create the Super Admin role if it doesn't exist
        if (!Role::where('name', 'Super Admin')->exists()) {
            Role::create(['name' => 'Super Admin']);
        }

        // Insert default Super Admin account if it doesn't exist
        if (!DB::table('users')->where('email', 'factoryadlab@gmail.com')->exists()) {
            $superAdminId = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => 'factoryadlab@gmail.com',
                'password' => Hash::make('Admin@2025'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign the Super Admin role
            DB::table('model_has_roles')->insert([
                'role_id' => Role::where('name', 'Super Admin')->first()->id,
                'model_type' => 'App\Models\User',
                'model_id' => $superAdminId,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
