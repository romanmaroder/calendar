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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('middleName')->nullable();
            $table->string('phone')->nullable();
            $table->string('comment',1000)->nullable();
            $table->boolean('blacklist')->nullable();
            $table->boolean('prepayment')->nullable();
            $table->smallInteger('discount')->nullable();
            $table->integer('records')->nullable();
            $table->integer('total')->nullable();
            $table->string('source')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('clients');

    }
};
