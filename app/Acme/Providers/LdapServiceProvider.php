<?php namespace Acme\Providers;

use Illuminate\Support\ServiceProvider;

class LdapServiceProvider extends ServiceProvider{ 

	/**
	 * Register the binding.
	 *
	 * @return void 
	 */
	public function register()
	{
		$app = $this->app;

		$app->bind('ldap', 'Acme\Services\Authentication\Ldap');
	}
}