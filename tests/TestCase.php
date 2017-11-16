<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Token\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;


    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);

    }

    private function setUpDatabase($app)
    {

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->string('id')->primary();
        });

        include_once __DIR__ . '/support/models.php';
    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            ServiceProvider::class
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
    }
}
