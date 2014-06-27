<?php namespace Acme\Interfaces\Db;

interface UserRepositoryInterface {

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The LDAP output data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function create( $data = array() );

	/**
	 * Fetch a specific model from the database.
	 *
	 * @param  int|string $id   The model's identifying value.
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function find( $id, $with = array() , $columns = array('*'));

	/**
	 * Fetch user from the database by their username.
	 *
	 * @param  string $username	The username of the user.
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function findByUsername( $username, $with = array() , $columns = array('*'));
}