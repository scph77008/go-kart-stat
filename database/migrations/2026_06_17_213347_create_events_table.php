<?php

use App\Enums\EventDurationType;
use App\Enums\EventType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название');
            $table
                ->foreignId('championship_id')
                ->nullable(false)
                ->comment('Чемпионат')
                ->constrained('championships')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->date('date')
                ->after('championship_id')
                ->comment('Дата');

            $table->integer('duration')
                ->after('date')
                ->comment('Длительность');

            $table->enum('duration_type', array_column(EventDurationType::cases(), 'value'))
                ->default(EventDurationType::LAPS->value)
                ->comment('Тип длительности');

            $table
                ->foreignId('track_id')
                ->nullable(false)
                ->comment('Картодром')
                ->constrained('tracks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->after('duration');

            $table->boolean('reverse')
                ->default(false)
                ->comment('В обратку');

            $table->enum('type', array_column(EventType::cases(), 'value'))
                ->default(EventType::TEAM->value)
                ->comment('Event type');

            $table->integer('participants')
                ->default(0)
                ->comment('Участники');

            $table->timestamps();

            $table->index('championship_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
