<?php namespace Acme\Providers;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider {

	/**
	 * Register the binding.
	 *
	 * @return void 
	 */
	public function register()
	{
		$app = $this->app;

		$app->bind('Acme\Interfaces\Db\AttachmentRepositoryInterface', 'Acme\Repositories\Db\AttachmentRepository');
		$app->bind('Acme\Interfaces\Db\DivisionRepositoryInterface', 'Acme\Repositories\Db\DivisionRepository');
		$app->bind('Acme\Interfaces\Db\ItemRepositoryInterface', 'Acme\Repositories\Db\ItemRepository');
		$app->bind('Acme\Interfaces\Db\LocationRepositoryInterface', 'Acme\Repositories\Db\LocationRepository');
		$app->bind('Acme\Interfaces\Db\PoRepositoryInterface', 'Acme\Repositories\Db\PoRepository');
		$app->bind('Acme\Interfaces\Db\UserRepositoryInterface', 'Acme\Repositories\Db\UserRepository');
	}
}