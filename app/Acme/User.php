<?php namespace Acme;

use Auth;
use Config;
use Ldap;
use Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'display_name',
		'email',
		'first_name',
		'groups',
		'last_name',
		'manager',
		'password',
		'username'
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;	

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/** 
	 * Determine if the user is a accounting or administration member.
	 * 
	 * @return boolean 
	 */
	public function isAdmin()
	{
		//  The group people must be a part of to administer the application.
		//  
		$needle   = Config::get('accounting.approval_group');

		// Fetch the groups the user is a part of.
		// 
		$haystack = json_decode(Auth::user()->groups);

		// Now see if the $needle is in the $haystack.
		// 
		return in_array($needle, $haystack);
	}

	/** 
	 * Fetch the manager's user record.
	 * 
	 * @return array.
	 */
	public function manager()
	{
		return (object) Ldap::searchForUser( $this->attributes['manager'] );
	}

	/** 
	 * Fetch the manager's user record.
	 * 
	 * @return array.
	 */
	public function managerUser()
	{
		return self::firstOrCreate((array) self::manager());
	}

	/**
	 * Get all purchase orders submitted by the user.
	 * 
	 * @return Acme\Po
	 */
	public function pos()
	{
		return $this->hasMany('Acme\Po', 'submitter_id');
	}
}