<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Token\ServiceProvider;
use ForTheLocal\Laravel\Token\ServiceTestProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;


    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            ServiceProvider::class,
            ServiceTestProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

//        $app['config']->set('database.default', 'mysql');
//        $app['config']->set('database.connections.mysql', [
//            'driver' => 'mysql',
//            'host' => env('DB_HOST', '127.0.0.1'),
//            'port' => env('DB_PORT', '3306'),
//            'database' => env('DB_DATABASE', 'test'),
//            'username' => env('DB_USERNAME', 'root'),
//            'password' => env('DB_PASSWORD', ''),
//            'unix_socket' => env('DB_SOCKET', ''),
//            'charset' => 'utf8mb4',
//            'collation' => 'utf8mb4_unicode_ci',
//            'prefix' => '',
//            'strict' => true,
//            'engine' => null,
//        ]);

        include_once __DIR__ . '/support/models.php';
    }
}
