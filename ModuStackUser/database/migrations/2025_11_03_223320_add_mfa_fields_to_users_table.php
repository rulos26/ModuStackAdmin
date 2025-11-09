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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('mfa_enabled')->default(false)->after('password');
            $table->string('mfa_secret')->nullable()->after('mfa_enabled');
            $table->timestamp('mfa_enabled_at')->nullable()->after('mfa_secret');
            $table->boolean('is_active')->default(true)->after('mfa_enabled_at');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->string('avatar')->nullable()->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mfa_enabled',
                'mfa_secret',
                'mfa_enabled_at',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'avatar',
            ]);
        });
    }
};
