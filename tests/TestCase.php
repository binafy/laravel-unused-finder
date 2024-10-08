<?php

namespace Tests;

use Binafy\LaravelUnusedFinder\Providers\LaravelUnusedFinderServiceProvider;
use Illuminate\Encryption\Encrypter;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load package service provider.
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelUnusedFinderServiceProvider::class];
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Set app key
        $app['config']->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey(config()['app.cipher'])
        ));
    }
}
