<?php

namespace App\Observers;

use App\Models\Entry;
use App\Models\EntryResult;
use App\Models\ResultCategory;

class EntryObserver
{
    /**
     * Создаём результат при создании участия
     *
     * @param \App\Models\Entry $entry
     * @return void
     */
    public function created(Entry $entry): void
    {
        $categories = ResultCategory::where('event_id', $entry->event_id)
            ->where('is_required', true)
            ->get();

        foreach ($categories as $category) {
            EntryResult::create([
                'entry_id' => $entry->id,
                'result_category_id' => $category->id,
            ]);
        }
    }
}
