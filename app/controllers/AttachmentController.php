<?php

use Acme\Interfaces\Db\AttachmentRepositoryInterface as Attachment;
use Acme\Interfaces\Db\PoRepositoryInterface as Po;
use Acme\Services\Validation\AttachmentValidator as Validator;

class AttachmentController extends \BaseController {

	/** 
	 * The attachment repository implementation
	 * .
	 * @var Acme\Repositories\Db\AttachmentRepository
	 */
	protected $attachments;

	/** 
	 * The attachment validator implementation
	 * .
	 * @var Acme\Services\Validation\AttachmentValidator
	 */
	protected $validator;

	/** 
	 * The purchase order repository implementation
	 * .
	 * @var Acme\Repositories\Db\PoRepository
	 */
	protected $po;

	/** 
	 * Create new AttachmentController instance.
	 * 
	 * @param Po $po
	 */
	public function __construct( Attachment $attachments , Validator $validator, Po $po )
	{
		$this->attachments = $attachments;
		$this->po = $po;
		$this->validator = $validator;

		// Execute BaseController::__construct()
		// 
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( $po )
	{
		// Fetch the purchase order record from the database.
		// 
		$po = $this->po->find( $po );

		// Assign content to the layout
		// 
		$this->layout->content = View::make('pos.attachments.index')->withPo(new PoPresenter($po));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store( $po )
	{
		// Fetch the purchase order record from the database.
		// 
		$po = $this->po->find( $po );

		// Capture the files from postback.
		// 
		$files = Input::file('files');
		
		// Given that we show 4 upload files by default does not mean that 4 will be
		// uploaded each time. This will filter out any blank submissions.
		// 
		$files = array_filter($files, 'strlen');

		// Capture the postback data.
		// 
		$input = Input::all();

		// Error container. 
		// 
		$errors = array();

		// The submitted po data did not pass validation.
		// 
		if( ! $this->validator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$fileErrors = $this->validator->errors();

			// Errors Exist
			// 
			if( count( $fileErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $fileErrors->getMessages());
			}
		}
		// Count the number of error messages.
		// 
		$errorCount = count($errors);

		// Create new attachment record if there are no errors.
		// 
		if( ! $errorCount)
		{	
			// Define an upload counter.
			// 
			$uploadCounter = 0;

			// Loop through each file and create a new record.
			// 
			foreach( $files as $file )
			{
				$data = array(
					'extension'	     => File::extension($file->getClientOriginalName()),
					'local_filename' => $file->getClientOriginalName(),
					'mime'			 => $file->getClientMimeType(),
					'po_id'          => $po->id,
				);

				// Create a new attachment instance.
				// 
				$instance = $this->attachments->instance($data);

				// Generate a new attachment and related it to the po.
				// 
				$record = $po->attachments()->save($instance);

				// Set where the uploads will be placed.
				// 
				$uploads_path = app_path() . '\uploads\\';

				// Move the file(s) into temporary, local storage.
				// 
				$file->move($uploads_path, $record->cloud_filename);

				// Increment the upload counter.
				// 
				$uploadCounter++;
			}

			// Push the actual files to upload into the queue.
			// 
			Queue::push(function($job)
			{
				CloudStorage::loopLoad();

				$job->delete();
			});

			// Process the next job on a queue
			// 
			Artisan::call('queue:work');

			// Flash a message that tells the user their po was successfully submitted.
			// 
			Session::flash('files_uploaded_successfully', $uploadCounter . ' '.Lang::choice('messages.files', $uploadCounter).' successfully been uploaded.');
			
			// Redirect the user back to their PO list page.
			// 
			return Redirect::route('pos.attachments.index', $po->getPoIdentifier());
		}

		// Go back to the form and display error messages.
		// 
		return $this->redirectBackWithErrors($errors);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show( $po , $id )
	{
		// Fetch the attachment record from the database.
		// 
		$attachment = $this->attachments->find($id);

		// Fetch the file from cloud storage.
		// 
		$file = CloudStorage::get( $attachment->cloud_filename );

		// Prepare a new response which will trigger a file download.
		// 
		$response = Response::make($file['Body'], 200);
		$response->header('Content-Type', $file['ContentType']);
		$response->header('Content-Disposition', "attachment; filename=" . $attachment->local_filename );

		// Now return the response.
		// 
		return $response;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($po, $attachment)
	{
		// Return the repsonse of the deletion.
		// 
		return Response::json(
			$this->attachments->destroy($attachment)
		);
	}

}