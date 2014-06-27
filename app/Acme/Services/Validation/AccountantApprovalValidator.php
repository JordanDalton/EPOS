<?php namespace Acme\Services\Validation;

class AccountantApprovalValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'password' => 'required'
	);
}