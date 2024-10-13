<?php

use Binafy\LaravelUnusedFinder\Commands\FindUnusedClasses;
use function Pest\Laravel\artisan;

test('can detect unused classes successfully', function () {
    artisan(FindUnusedClasses::class)
        ->expectsQuestion('Write the directory path:', 'app')
        ->assertSuccessful();
});
