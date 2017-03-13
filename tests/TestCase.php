<?php


use Napso\Lunytags\LunyTagsServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load your package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [LunyTagsServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();

        // ignore mass assignment alerts
        Eloquent::unguard();

        $this->artisan('migrate', [
            '--database' => 'testbench',
//            '--path' => realpath(__DIR__ . '/../migrations'),
        ]);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // create some table that we can tag
        \Schema::create('pages', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->timestamps();


        });
    }

    public function tearDown()
    {
        \Schema::drop('pages');
    }


}
