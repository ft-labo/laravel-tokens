<?php

namespace ForTheLocal\Laravel\Token;

/**
 * Class ServiceProvider
 * @package ForTheLocal\Laravel\Token
 */
class ServiceTestProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../tests/migrations');
    }


}