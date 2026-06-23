<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('events')
            ->select(['id', 'participants'])
            ->orderBy('id')
            ->each(function (object $event): void {
                DB::table('result_categories')->updateOrInsert(
                    [
                        'event_id' => $event->id,
                        'name' => 'Абсолют',
                    ],
                    [
                        'participants' => $event->participants,
                        'is_required' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );
            });
    }

    public function down(): void
    {
        DB::table('result_categories')
            ->where('name', 'Абсолют')
            ->delete();
    }
};
