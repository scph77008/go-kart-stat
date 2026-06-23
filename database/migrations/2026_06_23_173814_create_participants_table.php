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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->enum('type', \App\Filament\Resources\Events\EventType::cases())
                ->default(\App\Filament\Resources\Events\EventType::TEAM)
                ->comment('Тип события');
            $table
                ->integer('team_id')
                ->comment('Команда');
            $table
                ->integer('pilot_id')
                ->comment('Пилот');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
