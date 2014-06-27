<?php namespace Acme\Repositories\Db;

use Acme\Location;
use Acme\Interfaces\Db\LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface {

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all( $with = array() , $columns = array('*'))
	{
		return Location::with( $with )->remember(1440, 'query.location.all')->get( $columns );
	}
}