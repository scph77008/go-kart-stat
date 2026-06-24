<?php

namespace App\Providers;

use App\Models\Entry;
use App\Models\Pilot;
use App\Models\Team;
use App\Observers\EntryObserver;
use App\Observers\PilotObserver;
use App\Observers\TeamObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Entry::observe(EntryObserver::class);
        Pilot::observe(PilotObserver::class);
        Team::observe(TeamObserver::class);
    }
}
