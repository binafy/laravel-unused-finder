<?php

namespace Binafy\LaravelUnusedFinder\Providers;

use Binafy\LaravelUnusedFinder\Commands\FindUnusedClasses;
use Illuminate\Support\ServiceProvider;

class LaravelUnusedFinderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            FindUnusedClasses::class,
        ]);
    }
}
