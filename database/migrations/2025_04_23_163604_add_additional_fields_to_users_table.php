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
            $table->boolean('blacklist')->nullable()->after('comment');
            $table->boolean('prepayment')->nullable()->after('blacklist');
            $table->smallInteger('discount')->nullable()->after('prepayment');
            $table->integer('records')->nullable()->after('discount');
            $table->integer('total')->nullable()->after('records');
            $table->string('source')->nullable()->after('total');
            $table->string('avatar')->nullable()->after('source');
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
           $table->dropColumn('blacklist');
           $table->dropColumn('prepayment');
           $table->dropColumn('discount');
           $table->dropColumn('records');
           $table->dropColumn('total');
           $table->dropColumn('source');
           $table->dropColumn('avatar');
        });
    }
};
