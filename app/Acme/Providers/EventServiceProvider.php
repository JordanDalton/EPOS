<?php namespace Acme\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	public function register()
	{
		$app = $this->app;

		//$app->bind('PoEventHandler', 'Acme\Events\PoEventHandler');
		$app->bind('PoEventHandler', function()
		{
			return new \Acme\Events\PoEventHandler( \App::make('Acme\Interfaces\Db\UserRepositoryInterface'));
		});

		$app['events']->subscribe( $app->make('PoEventHandler'));
	}	
}