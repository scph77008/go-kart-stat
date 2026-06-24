<?php

use App\Filament\Resources\Events\EventDurationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {

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

            $table->index('track_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_track_id_index');
            $table->dropConstrainedForeignId('track_id');
            $table->dropColumn([
                'date',
                'duration',
                'duration_type',
            ]);
        });
    }
};
