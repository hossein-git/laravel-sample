<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['private', 'business'])->default('private');
            $table->string('name', 100);
            $table->string('address');
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 50);
            $table->string('zip', 100)->nullable();
            $table->string('tax_type', 50)->nullable();
            $table->string('tax_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_addresses');
    }
};
