<?php

use Binafy\LaravelUnusedFinder\Commands\FindUnusedClasses;

use function Pest\Laravel\artisan;

test('can detect unused classes successfully', function () {
    artisan(FindUnusedClasses::class)
        ->expectsQuestion('Write the directory path:', 'app')
        ->assertSuccessful();
});

test('can detect unused classes successfully with except classes', function () {
    config(['laravel-unused-finder.classes.excluded' => 'app/Models/User.php']);

    artisan(FindUnusedClasses::class)
        ->expectsQuestion('Write the directory path:', 'app')
        ->assertSuccessful();
});
