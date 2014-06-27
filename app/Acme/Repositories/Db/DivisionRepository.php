<?php namespace Acme\Repositories\Db;

use Acme\Division;
use Acme\Interfaces\Db\DivisionRepositoryInterface;

class DivisionRepository implements DivisionRepositoryInterface {

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all( $with = array() , $columns = array('*'))
	{
		return Division::with( $with )->remember(1440, 'query.division.all')->get( $columns );
	}
}