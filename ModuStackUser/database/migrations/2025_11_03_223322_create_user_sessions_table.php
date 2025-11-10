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
        if (! Schema::hasTable('user_sessions')) {
            Schema::create('user_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('session_id')->unique();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('device_type')->nullable(); // desktop, mobile, tablet
                $table->string('browser')->nullable();
                $table->string('platform')->nullable(); // Windows, macOS, Linux, iOS, Android
                $table->string('location')->nullable(); // Country/City
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_activity')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
                $table->index('last_activity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
