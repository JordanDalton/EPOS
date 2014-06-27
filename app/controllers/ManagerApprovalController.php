<?php

use Acme\Interfaces\Db\PoRepositoryInterface as Po;
use Acme\Services\Validation\ManagerApprovalValidator as Validator;

class ManagerApprovalController extends \BaseController {

	/** 
	 * The purchase order repository implementation
	 * .
	 * @var Acme\Repositories\Db\PoRepository
	 */
	protected $po;

	/**
	 * The manager approval validator implementation.
	 * 
	 * @var Acme\Services\Validation\ManagerApprovalValidator
	 */
	protected $validator;

	/** 
	 * Create new AttachmentController instance.
	 * 
	 * @param Po $po
	 */
	public function __construct( Po $po, Validator $validator )
	{
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

		// Bail if the purchase order does not exist.
		// 
		if(  is_null($po) ) return App::abort(404, "The purchase order you're attempting to view has been removed or does not exist.");

		// Bail if the user is not allowed to access the page.
		// 
		if( ! Auth::user()->isAdmin() ) return App::abort(403, 'You are not allowed to access this page.');

		// Assign content to the layout
		// 
		$this->layout->content = View::make('pos.manager-approval.index')->withPo(new PoPresenter($po));
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

		// The submitted data did not pass validation.
		// 
		if( ! $this->validator->validate( Input::all() ) )
		{
			// Capture the validation error message(s).
			// 
			$errors = $this->validator->errors();

			// Go back to the form.
			// 
			return $this->redirectBack( compact('errors') );
		}

		//-------------------------------
		// Manager has been authenticated.
		//-------------------------------

		// Update the purchase order record to show as approved.
		// 
		$update = $this->po->managerApproved( $po->getPoIdentifier(), Input::get('manager') );

		// Flash a message that tells the user they have successfully logged out.
		// 
		Session::flash('manager_approval_successful', 'The purchase order has been approved.');

		// Send the user to the purchase order list page.
		// 
		return $this->redirectRoute('pos.manager-approval.index', $po->getPoIdentifier());
	}
}