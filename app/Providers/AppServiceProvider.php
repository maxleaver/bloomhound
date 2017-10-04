<?php

namespace App\Providers;

use App\FlowerVarietySource;
use App\Observers\FlowerVarietySourceObserver;
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
        FlowerVarietySource::observe(FlowerVarietySourceObserver::class);
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
