<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Filament\Resources\Events\EventType;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('participants', 'participants_old');

        if (DB::getDriverName() === 'sqlite') {
            $indexes = collect(DB::select("PRAGMA index_list('participants_old')"))
                ->pluck('name');

            if ($indexes->contains('participants_type_team_id_pilot_id_unique')) {
                DB::statement('DROP INDEX participants_type_team_id_pilot_id_unique');
            }
        } else {
            Schema::table('participants_old', function (Blueprint $table) {
                $table->dropUnique(['type', 'team_id', 'pilot_id']);
            });
        }

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(EventType::cases(), 'value'))->default(EventType::TEAM->value);
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pilot_id')->nullable()->constrained('pilots')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['type', 'team_id', 'pilot_id']);
        });

        DB::table('participants_old')
            ->orderBy('id')
            ->each(function (object $participant): void {
                $type = $participant->type;
                $teamId = $type === EventType::TEAM->value ? $participant->team_id : null;
                $pilotId = $type === EventType::INDIVIDUAL->value ? $participant->pilot_id : null;

                if ($teamId === null && $pilotId === null) {
                    return;
                }

                DB::table('participants')->insertOrIgnore([
                    'id' => $participant->id,
                    'type' => $type,
                    'team_id' => $teamId,
                    'pilot_id' => $pilotId,
                    'created_at' => $participant->created_at,
                    'updated_at' => $participant->updated_at,
                ]);
            });

        Schema::drop('participants_old');

        Schema::table('entries', function (Blueprint $table) {
            $table->foreignId('participant_id')
                ->nullable()
                ->after('event_id')
                ->constrained('participants')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('number')->nullable()->after('participant_id');
            $table->text('comment')->nullable()->after('number');
        });

        DB::table('entries')
            ->select('team_id')
            ->whereNotNull('team_id')
            ->distinct()
            ->orderBy('team_id')
            ->each(function (object $entry): void {
                DB::table('participants')->insertOrIgnore([
                    'type' => EventType::TEAM->value,
                    'team_id' => $entry->team_id,
                    'pilot_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        DB::statement(
            "UPDATE entries
             SET participant_id = (
                 SELECT id
                 FROM participants
                 WHERE participants.type = 'team'
                   AND participants.team_id = entries.team_id
                 LIMIT 1
             )"
        );

        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn(['team_id', 'position']);
        });

        Schema::create('entry_pilots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pilot_id')->constrained('pilots')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['entry_id', 'pilot_id']);
        });

        Schema::create('result_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->unsignedInteger('participants')->default(0);
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->unique(['event_id', 'name']);
        });

        Schema::create('entry_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('result_category_id')->constrained('result_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('position')->nullable();
            $table->string('gap')->nullable();
            $table->timestamps();

            $table->unique(['entry_id', 'result_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_results');
        Schema::dropIfExists('result_categories');
        Schema::dropIfExists('entry_pilots');

        Schema::table('entries', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('event_id')->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('position')->default(0)->after('team_id');
        });

        DB::statement(
            "UPDATE entries
             SET team_id = (
                 SELECT team_id
                 FROM participants
                 WHERE participants.id = entries.participant_id
                 LIMIT 1
             )"
        );

        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->dropColumn(['participant_id', 'number', 'comment']);
        });
    }
};
