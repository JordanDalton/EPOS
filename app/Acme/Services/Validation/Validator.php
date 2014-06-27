<?php namespace Acme\Services\Validation;

use Validator as V;

abstract class Validator {

	/**
	 * Error messages container.
	 * 
	 * @var array
	 */
	protected $errors;

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	protected static $rules;

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	protected static $messages = array();

	/**
	 * Return the validation rules.
	 * 
	 * @return array
	 */
	public function getRules()
	{
		return static::$rules;
	}

	/**
	 * Return the validation messages.
	 * 
	 * @return array
	 */
	public function getMessages()
	{
		return static::$messages;
	}

	/**
	 * Set the validation rules.
	 * 
	 * @param array $rules
	 */
	public function setRules( $rules = array() )
	{
		foreach($rules as $key => $value )
		{
			$this->setRule($key, $value);
		}
	}

	/**
	 * Set the validation messages.
	 * 
	 * @param array $messages
	 */
	public function setMessages( $messages = array() )
	{
		foreach($messages as $key => $value )
		{
			$this->setMessage($key, $value);
		}
	}

	/**
	 * Fetch an individual rule.
	 * 
	 * @param string $key   The key name
	 */
	public function getRule($key, $value)
	{
		return static::$rules[$key];
	}

	/**
	 * Fetch an individual message.
	 * 
	 * @param string $key   The key name
	 */
	public function getMessage($key, $value)
	{
		return static::$messages[$key];
	}

	/**
	 * Set a individual rule.
	 * 
	 * @param string $key   The key name
	 * @param string $value The validation rule(s).
	 */
	public function setRule($key, $value)
	{
		static::$rules[$key] = $value;
	}

	/**
	 * Set a individual message.
	 * 
	 * @param string $key   The key name
	 * @param string $value The validation message(s).
	 */
	public function setMessage($key, $value)
	{
		static::$messages[$key] = $value;
	}

	/**
	 * Unset an individual rule.
	 * 
	 * @param string $key   The key name
	 */
	public function unsetRule($key)
	{
		unset( static::$rules[$key] );
	}

	/**
	 * Unset an individual message.
	 * 
	 * @param string $key   The key name
	 */
	public function unsetMessage($key)
	{
		unset( static::$messages[$key] );
	}

	/**
	 * Validate the input data against validation rules.
	 * @param  array $input The input data.
	 * @return boolean        
	 */
	public function validate( $input )
	{
		// Create new validator instance.
		// 
		$validator = V::make( $input , static::$rules, static::$messages );

		// Input failed validation.
		// 
		if( $validator->fails() )
		{
			// Capture and assign the error messages to the $errors container.
			// 
			$this->errors = $validator->messages();

			// Return that validation failed.
			// 
			return false;
		}

		// By default return that validation was successful.
		// 
		return true;
	}

	/**
	 * Fetch the errors.
	 * 
	 * @return array 
	 */
	public function errors()
	{
		return $this->errors;
	}
}