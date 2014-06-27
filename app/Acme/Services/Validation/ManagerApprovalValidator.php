<?php namespace Acme\Services\Validation;

class ManagerApprovalValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'manager' => 'required|min:2'
	);
}