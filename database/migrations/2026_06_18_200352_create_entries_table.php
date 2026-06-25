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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('event_id')
                ->nullable(false)
                ->comment('Событие')
                ->constrained('events')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->enum('entrant_type', array_column(EventType::cases(), 'value'))
                ->default(EventType::TEAM->value)
                ->comment('Тип участника');

            $table
                ->unsignedBigInteger('entrant_id')
                ->nullable()
                ->comment('ID участника');

            $table
                ->unsignedInteger('number')
                ->nullable()
                ->comment('Номер участника');

            $table->boolean('position')
                ->default(0)
                ->comment('Позиция');

            $table->timestamps();

            $table->index(['entrant_type', 'entrant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
