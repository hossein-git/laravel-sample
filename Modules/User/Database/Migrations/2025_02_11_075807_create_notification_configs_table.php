<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('notification_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('strategy_id')->index();
            $table->string('chart_namespace')->index();
            $table->string('type', 100)->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('strategy_id')->references('id')->on('strategies');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_configs');
    }
};
