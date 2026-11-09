<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('max_margin_level', 12, 4)->default(65)->after('account_number')
                ->comment('Percent: max allowed margin level');
            $table->decimal('max_deposit_load', 12, 4)->default(65)->after('max_margin_level')
                ->comment('Percent: max allowed deposit load');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['max_margin_level', 'max_deposit_load']);
        });
    }
};
