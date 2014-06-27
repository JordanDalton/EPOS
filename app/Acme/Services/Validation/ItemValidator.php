<?php namespace Acme\Services\Validation;

class ItemValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'po.items' => 'Array'
	);

	/**
	 * Define grouped validation rules.
	 * 
	 * @var array
	 */
	public static $group_rules = array(
		'description' => 'required',
		'due_date' 	  => '',
		'qty' 		  => 'integer',
		'tax' 		  => 'numeric|between:0,9999999',
		'total' 	  => 'required|numeric|between:0,9999999',
		'uc' 		  => '',
		'uc_um' 	  => '',
		'um' 		  => ''
	);

	/**
	 * Define grouped validation messages. To be used with sprintf().
	 * 
	 * @var array
	 */
	public static $group_messages = array(
		'description' => array(
			'required' => 'The descripion field on line #%d is required.'
		),
		'due_date' => array(),
		'qty' => array(
			'integer' => 'The qty field on line #%d must be an integer.'
		),
		'tax' => array(
			'numeric' =>  'The tax field on line #%d must be a number.'
		),
		'total' => array(
			'required' => 'The total field on line #%d is required.', 
			'numeric' => 'The total field on line #%d must be a number.'
		),
		'uc' 		  => array(),
		'uc_um' 	  => array(),
		'um' 		  => array()
	);

	/**
	 * Validate the input data against validation rules.
	 * 
	 * @param  array $input The input data.
	 * @return boolean        
	 */
	public function validate( $input )
	{
		$items = array_get($input, 'po.items');

		// We will dynamically set validation rules for each item that was submitted.
		// 
		if( count($items) )
		{			
			// Loop through each item submission.
			// 
			foreach( $items as $key => $value )
			{
				// Loop through our list of static rules and generate validation
				// rules for each item.
				// 
				foreach( static::$group_rules as $grk => $grv )
				{
					// Generate validation rule.
					// 
					$this->setRule("po.items.$key.$grk", "$grv");

					// Now generate a validation message(s) for each validation rule.
					// 
					foreach( static::$group_messages[$grk] as $gmk => $gmv )
					{
						$this->setMessage("po.items.$key.$grk.$gmk", sprintf($gmv, $key));
					}
				}
			}

			// Execute Validator::validate($input);
			// 
			return parent::validate( $input );
		}
	}
}