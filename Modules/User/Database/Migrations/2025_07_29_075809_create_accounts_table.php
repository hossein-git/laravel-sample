<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('broker_id')->constrained('brokers');
            $table->unsignedtinyInteger('status')->nullable();
            $table->timestamp('latest_activity')->nullable();
            $table->string('account_number');
            $table->timestamps();
        });

        Schema::table('mql5_entries', function (Blueprint $table) {
            $table->foreignId('account_id')->after('user_id')->nullable()->constrained('accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('mql5_entries', function (Blueprint $table) {
            $table->dropForeign('mql5_entries_account_id_foreign');
            $table->dropColumn('account_id');
        });

        Schema::dropIfExists('accounts');
    }
};
