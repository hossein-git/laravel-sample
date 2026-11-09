<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Trade\Models\Chart;
use Modules\User\Models\Broker;
use Modules\User\Models\User;

return new class extends Migration {
    public function up(): void
    {
        foreach (self::chartTables() as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'broker_id')) {
                    $table->foreignId('broker_id')->after('id')->nullable()->constrained('brokers')->cascadeOnDelete();
                }
                if (! Schema::hasColumn($tableName, 'user_id')) {
                    $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->cascadeOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (self::chartTables() as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'broker_id')) {
                $fkName = $tableName . '_broker_id_foreign';
                if ($this->foreignKeyExists($tableName, $fkName)) {
                    Schema::table($tableName, function (Blueprint $table) use ($fkName) {
                        $table->dropForeign($fkName);
                    });
                }
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('broker_id');
                });
            }

            if (Schema::hasColumn($tableName, 'user_id')) {
                $fkName = $tableName . '_user_id_foreign';
                if ($this->foreignKeyExists($tableName, $fkName)) {
                    Schema::table($tableName, function (Blueprint $table) use ($fkName) {
                        $table->dropForeign($fkName);
                    });
                }
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
            }
        }
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        return ! empty(DB::select(
            'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?
             LIMIT 1',
            [$table, $constraintName, 'FOREIGN KEY']
        ));
    }

    private static function chartTables(): array
    {
        return Chart::allCandleTableNames();
    }
};
