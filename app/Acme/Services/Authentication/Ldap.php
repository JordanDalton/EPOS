<?php namespace Acme\Services\Authentication;

use App;
use Cache;
use Config;
use Illuminate\Support\Collection;

class Ldap {

	/**
	 * LDAP Server Connection Resource.
	 * 
	 * @var Resource|false
	 */
	protected $connection = false;

	/**
	 * User Data
	 * 
	 * @var array|false
	 */
	protected $user = false;

	/** 
	 * Create a new LDAP instance.
	 */
	public function __construct()
	{
		$this->connect();
	}

	/**
	 * Etablish a connection with the LDAP server.
	 */
	protected function connect()
	{
		$this->connection = ldap_connect(Config::get('ldap.server')) or die('Could not connect to the LDAP server.');

		return $this;
	}

	/**
	 * Authenticate user credentials against ldap.
	 * @param  string $username 
	 * @param  string $password 
	 * @return array|false
	 */
	public function authenticate( array $input = array() )
	{
		return $this->bind( $input['username'] , $input['password'] );
	}

	/**
	 * Take the user adjusted and reformat it.
	 * 
	 * @param  array  $user 
	 * @return array
	 */
	public function adjustedUser( array $user = array() )
	{
		// Fetch from auth configuration, the ldap fields we need data from.
		// 
		$columns = Config::get('ldap.fields');

		// Extract just the key value from $columns. These value are the
		// identifying key values from the ldap response.
		// 
		$keys = array_keys($columns);

		// Here we take the ldap response data and filter out just the 
		// data that we're requiring.
		// 
		$filtered_data = array_only($user[0], $keys);

		// We will need to iterate through the data and ignore/keep
		// certain subkey values.
		// 
		$data = array_map(function($record)
		{
			// Determine how many rows of data exist.
			// 
			$count = $record['count'];

			// Ignore count reference.
			// 
			$data = array_except($record, array('count'));

			// Only return array if there is more than 1 record.
			// 
			return $count >= 2 ? json_encode($data) : $data[0];

		}, $filtered_data);

		// This array will contain the re-writtien data.
		// 
		$newData = array();

		// Now iterate through the old data and generate a new
		// data set which has the key values re-named.
		// 
		foreach( $data as $key => $value )
		{
			// Obtain what the new key value should be.
			// 
			$setKey = $columns[$key];

			// Now assign the new key/value pair.
			//
			$newData[$setKey] = $value;
		}

		// Unset the current user data.
		// 
		unset($this->user);

		// Reset with new data;
		// 
		$this->user = $newData;

		// Now return the new data.
		// 
		return $this->user;
	}

	/**
	 * Binds to the LDAP directory with specified RDN and password.
	 * 
	 * @param  string $username
	 * @param  string $password 
	 * @return boolean
	 */
	public function bind( $username , $password )
	{
		// Modify to be [username]@wntdom.wrsgroup.com
		// 
		$suffixed_username = sprintf('%s%s', $username, Config::get('ldap.suffix'));

		// Return the results of the 
		// 
		$bind = @ldap_bind($this->connection, $suffixed_username, $password);

		// If the bind was successful then we will fetch the user record.
		// 
		if( $bind )
		{
			$this->setUser( $this->fetchUser($username) );

			// Return that the binding was successful.
			// 
			return true;
		}

		// By default return false.
		// 
		return false;
	}

	/**
	 * Fetch a user record from ldap.
	 * 
	 * @param  string $username 
	 * @return array|false
	 */
	public function fetchUser( $username )
	{
		$dn = Config::get('ldap.dn');

		// Search LDAP for the user record.
		// 
		$search = ldap_search($this->connection, $dn, sprintf('(sAMAccountName=%s)', $username));

		// Obtain and assign the results.
		// 
		$this->user = ldap_get_entries($this->connection, $search);

		return $this->user;
	}

	/** 
	 * Search for a user's AD record by their CN.
	 * 
	 * @param  string $cn
	 * @return array
	 */
	public function searchForUser( $cn = '' )
	{
		$cn = preg_replace('/(CN|DC)=/','',$cn); 
		$cn = explode(',', $cn);
		$cn = $cn[0];

		$thisThis = $this;

		return Cache::get("ldap-{$cn}", function() use ($cn, $thisThis)
		{
			$dn = Config::get('ldap.dn');

			// Log in to ldap using admin credentials.
			// 
			$bind = $thisThis->bind(
				Config::get('ldap.admin.username'),
				Config::get('ldap.admin.password')
			);

			// Search LDAP for the user record.
			// 
			$search = ldap_search($this->connection, $dn, sprintf('(cn=%s)', $cn));

			// Obtain and assign the results.
			// 
			$result = ldap_get_entries($this->connection, $search);

			// Unbind the connection.
			// 
			Ldap::unbind();
			
			// Place the results into cache.
			// 
			Cache::put("ldap-{$cn}", Ldap::adjustedUser($result), 1440);

			// Now return the results.
			// 
			return Ldap::adjustedUser($result);
		});
	}

	/**
	 * Set the ldap user data.
	 * 
	 * @param array $user
	 * return array
	 */
	public function setUser( array $user = array() )
	{
		return $this->adjustedUser($user);
	}

	/**
	 * Unbinds from the LDAP directory.
	 *  
	 * @return boolean
	 */
	public function unbind()
	{
		return ldap_unbind($this->connection);
	}

	/**
	 * Return the user data.
	 * 
	 * @return array
	 */
	public function user()
	{
		return Collection::make((object) $this->user)->first();
	}
}