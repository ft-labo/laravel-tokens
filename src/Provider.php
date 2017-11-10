<?php

namespace ForTheLocal\Token;

use Illuminate\Support\ServiceProvider;

/**
 * Class TokenProvider
 * @package ForTheLocal\Token
 */
class Provider extends ServiceProvider
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