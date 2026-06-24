<?php

use App\Filament\Resources\Events\EventType;
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
            $table->enum('type', array_column(EventType::cases(), 'value'))->default(EventType::TEAM->value);
            $table->foreignId('team_id')
                ->nullable()
                ->comment('Team')
                ->constrained('teams')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('pilot_id')
                ->nullable()
                ->comment('Pilot')
                ->constrained('pilots')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['type', 'team_id', 'pilot_id']);
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
