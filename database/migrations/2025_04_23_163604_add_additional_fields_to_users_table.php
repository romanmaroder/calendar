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
            $table->string('surname')->nullable()->after('name');
            $table->string('middleName')->nullable()->after('surname');
            $table->string('phone')->nullable()->after('middleName');
            $table->string('comment',1000)->nullable()->after('phone');
            $table->string('birthday')->nullable()->after('comment');
            $table->string('avatar')->nullable()->after('birthday');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('surname');
           $table->dropColumn('middleName');
           $table->dropColumn('phone');
           $table->dropColumn('comment');
           $table->dropColumn('birthday');
           $table->dropColumn('avatar');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
