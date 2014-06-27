<?php namespace Acme\Repositories\Db;

use Acme\Interfaces\Db\UserRepositoryInterface;
use Acme\User as User;

class UserRepository implements UserRepositoryInterface {

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The LDAP output data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function create( $data = array() )
	{
		return User::firstOrCreate($data);
	}

	/**
	 * Fetch a specific model from the database.
	 *
	 * @param  int|string $id   The model's identifying value.
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function find( $id, $with = array() , $columns = array('*'))
	{
		return User::with( $with )->find( $columns );
	}

	/**
	 * Fetch user from the database by their username.
	 *
	 * @param  string $username	The username of the user.
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function findByUsername( $username, $with = array() , $columns = array('*'))
	{
		return User::with($with)->whereUsername($username)->first($columns);
	}
}