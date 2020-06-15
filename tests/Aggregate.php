<?php 

namespace Armincms\Targomaan\Tests;

use Orchestra\Testbench\TestCase;

class Aggregate extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
	    parent::setUp();


        $this->loadMigrations();

	    
	    $this->withFactories(__DIR__.'/factories');

	    // Your code here
	}

	public function test_for_ignoration()
	{
		$this->assertTrue(true);
	}

	protected function getPackageProviders($app)
	{
	    return [
	    	'Armincms\\Targomaan\\TargomaanServiceProvider',
	    ];
	}

    /**
     * Load the migrations for the test environment.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'targoman',
            '--path' => realpath(__DIR__.'/Migrations'),
            '--realpath' => true,
        ]);
    }

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
	    // Setup default database to use sqlite :memory:
	    $app['config']->set('database.default', 'targoman');

	    $app['config']->set('database.connections.targoman', [
	        'driver'   => 'sqlite',
	        'database' => ':memory:',
	        'prefix'   => '',
	    ]);
	}
}