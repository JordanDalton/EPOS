<?php namespace Acme\Interfaces\Db;

interface DivisionRepositoryInterface {

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all( $with = array() , $columns = array('*'));
}