<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Filament\Resources\Events\EventType;

return new class extends Migration {
    public function up(): void
    {
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

        Schema::table('entries', function (Blueprint $table) {
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

        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->dropColumn(['participant_id', 'number', 'comment']);
        });
    }
};
