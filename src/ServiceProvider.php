<?php

namespace ForTheLocal\Laravel\Token;

/**
 * Class ServiceProvider
 * @package ForTheLocal\Laravel\Token
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }


}