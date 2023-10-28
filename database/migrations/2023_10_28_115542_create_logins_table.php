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
        Schema::create('logins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('token')->nullable();
            $table->enum('action', ['LOGIN', 'LOGOUT', 'REGISTER', 'RESET_PASSWORD', 'FORGOT_PASSWORD', 'LOGIN_LINK', 'RESET_LINK', 'MULTI_FACTORS', 'LOGIN_ATTEMPT', 'CONFIRM_EMAIL'])->default('LOGIN');
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logins');
    }
};
