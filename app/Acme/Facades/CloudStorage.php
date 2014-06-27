<?php namespace Acme\Facades;

use Illuminate\Support\Facades\Facade;

class CloudStorage extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
	protected static function getFacadeAccessor() { return 'cloudstorage'; }
}