<?php

namespace App\Providers;

use App\Models\Entry;
use App\Observers\EntryObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Entry::observe(EntryObserver::class);
    }
}
