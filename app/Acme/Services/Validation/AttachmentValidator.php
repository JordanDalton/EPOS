<?php namespace Acme\Services\Validation;

class AttachmentValidator extends Validator {

	/**
	 * Define validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
		'files' => 'Array'
	);

	/**
	 * Validate the input data against validation rules.
	 * 
	 * @param  array $input The input data (the files)
	 * @return boolean
	 */
	public function validate( $input )
	{
		// Fetch just the files from the post data.
		// 
		$getFiles = array_get($input, 'files');

		// Given that we show 4 upload fiels by default does not mean that 4 will be
		// uploaded each time. This will filter out any blank submissions.
		// 
		$getFiles = array_filter($getFiles, 'strlen');

		// Dynamically set validation rules for each file
		// that we uploadded.
		// 
		if( count($getFiles) )
		{
			foreach( $getFiles as $key => $value )
			{
				// For now we will not define a mime list since there is not one for the .msg file type.
				// $this->setRule("files.$key", "required|mimes:csv,doc,docx,htm,html,jpeg,jpg,msg,pdf,png,rtf,txt,xls,xlsx");
				$this->setRule("files.$key", "required");
				$this->setMessage("files.$key.mimes", "File #$key must be a file of type: :values.");
			}
		}

		// Execute Validator::validate($input);
		// 
		return parent::validate($input);
	}
}