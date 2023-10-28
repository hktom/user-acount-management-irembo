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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nationality_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['MAN', 'WOMAN', 'OTHER'])->nullable();
            $table->string('email')->unique();
            $table->string('date_of_birth')->nullable();
            $table->enum('marital_status', ['SINGLE', 'MARRIED', 'DIVORCED', 'WIDOWED'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['UNVERIFIED', 'PENDING', 'VERIFICATION', 'VERIFIED'])->default('UNVERIFIED');
            $table->enum('role', ['ADMIN', 'USER'])->default('USER');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
