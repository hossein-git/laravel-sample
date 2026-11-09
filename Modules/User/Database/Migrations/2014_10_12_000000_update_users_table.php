<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('mt5_account_number')->nullable();
            $table->string('mt4_account_number')->nullable();
            $table->dateTime('trial_ends_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->tinyInteger('verified')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Update records with NULL values to avoid constraint violations
        DB::table('users')->whereNull('name')->update(['name' => '']);
        DB::table('users')->whereNull('password')->update(['password' => '']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'trial_ends_at',
                    'verification_code',
                    'verified',
                    'mt4_account_number',
                    'mt5_account_number',
                ]
            );

            $table->string('name')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->dropSoftDeletes();
        });
    }
};
