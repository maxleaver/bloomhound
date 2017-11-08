<?php

namespace App\Providers;

use App\Event;
use App\FlowerVarietySource;
use App\Proposal;
use App\Observers\EventObserver;
use App\Observers\FlowerVarietySourceObserver;
use App\Observers\ProposalObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Reduces default string length for databases like MariaDB
        Schema::defaultStringLength(191);

        // Enables foreign keys for SQLite databases
        if (\DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            \DB::statement(\DB::raw('PRAGMA foreign_keys=1'));
        }

        // Register model observers
        Event::observe(EventObserver::class);
        FlowerVarietySource::observe(FlowerVarietySourceObserver::class);
        Proposal::observe(ProposalObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
