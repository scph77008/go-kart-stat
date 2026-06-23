<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Entry;
use App\Models\ResultCategory;

return new class extends Migration {
    public function up(): void
    {
        $requiredCategories = ResultCategory::where('is_required', true)->get();

        Entry::chunkById(100, function ($entries) use ($requiredCategories) {
            /** @var Entry $entry */
            foreach ($entries as $entry) {

                /** @var ResultCategory $category */
                foreach ($requiredCategories as $category) {

                    if($category->event_id !== $entry->event_id) {
                        continue;
                    }

                    $exists = DB::table('entry_results')
                        ->where('entry_id', $entry->id)
                        ->where('result_category_id', $category->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('entry_results')->insert([
                            'entry_id' => $entry->id,
                            'result_category_id' => $category->id,
                            'position' => null,
                            'gap' => null,
                            'participants_count' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('entry_results')
            ->whereIn('result_category_id', function ($q) {
                $q->select('id')
                    ->from('result_categories')
                    ->where('is_required', true);
            })
            ->delete();
    }
};
