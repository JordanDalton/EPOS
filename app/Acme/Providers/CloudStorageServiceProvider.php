<?php namespace Acme\Providers;

use Illuminate\Support\ServiceProvider;

class CloudStorageServiceProvider extends ServiceProvider{ 

	/**
	 * Register the binding.
	 *
	 * @return void 
	 */
	public function register()
	{
		$this->app->bind(
			'Acme\Interfaces\CloudStorage\CloudStorageInterface',
			'Acme\CloudStorage\S3'
		);

        // Register 'cloudstorage' instance container to our UnderlyingClass object
        $this->app['cloudstorage'] = $this->app->share(function($app)
        {
            return new \Acme\CloudStorage\CloudStorage(
            	$app->make('Acme\Interfaces\CloudStorage\CloudStorageInterface')
        	);
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CloudStorage', 'Acme\Facades\CloudStorage');
        });
	}
}