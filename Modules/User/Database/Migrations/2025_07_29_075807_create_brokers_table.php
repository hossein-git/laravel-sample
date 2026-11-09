<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\User\Database\Seeders\BrokerSeeder;
use Modules\User\Models\Broker; // needed for createBrokers()

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('timezone');
            $table->timestamps();
        });

        Schema::create('broker_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('broker_id')
                ->constrained('brokers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $additions = [
            'mql5_entries' => ['after' => 'user_id', 'onDelete' => 'cascade'],
            'back_test_logs' => ['after' => 'chart_id', 'onDelete' => 'cascade'],
            'back_test_entries' => ['after' => 'strategy_id', 'onDelete' => 'cascade'],
            'chart_configs' => ['after' => 'id', 'onDelete' => 'null'],
            'back_test_init_results' => ['after' => 'id', 'onDelete' => 'null'],
        ];

        foreach ($additions as $tableName => $opts) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'broker_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($opts) {
                    $col = $table->foreignId('broker_id')->after($opts['after'])->nullable()->constrained('brokers')->cascadeOnUpdate();
                    $opts['onDelete'] === 'cascade' ? $col->cascadeOnDelete() : $col->nullOnDelete();
                });
            }
        }

        $this->createBrokers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $tables = ['mql5_entries', 'back_test_logs', 'back_test_entries', 'chart_configs', 'back_test_init_results'];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'broker_id')) {
                continue;
            }

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

        Schema::dropIfExists('broker_user');
        Schema::dropIfExists('brokers');
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

    private function createBrokers(): void
    {
        $brokers = [
            [
                'id' => BrokerSeeder::BACKTEST_BROKER_ID,
                'name' => 'backtest-broker',
                'timezone' => 'Europe/Athens',
            ],
            [
                'name' => 'Wingo Group Ltd',
                'timezone' => 'Europe/Athens',
            ],
            [
                'name' => 'Fusion Markets Pty Ltd',
                'timezone' => 'Europe/Athens',
            ],
            [
                'name' => 'IC Markets (EU) Ltd',
                'timezone' => 'Europe/Athens',
            ],
        ];

        foreach ($brokers as $value) {
            Broker::query()->create($value);
        }
    }
};
