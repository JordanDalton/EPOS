<?php

use Acme\Interfaces\Db\PoRepositoryInterface as Po;
use Acme\Interfaces\Db\DivisionRepositoryInterface as Division;
use Acme\Interfaces\Db\ItemRepositoryInterface as Item;
use Acme\Interfaces\Db\LocationRepositoryInterface as Location;
use Acme\Services\Presentation\PresenterCollection;
use Acme\Services\Validation\DivisionValidator;
use Acme\Services\Validation\LocationValidator;
use Acme\Services\Validation\PoValidator;
use Acme\Services\Validation\ItemValidator;

class PoController extends \BaseController {

	/**
	 * The division repositor implementation.
	 * 
	 * @var Acme\Repositories\Db\DivisionRepository
	 */
	protected $divisions;

	/**
	 * The division validator implementation.
	 * 
	 * @var Acme\Services\Validation\DivisionValidator
	 */
	protected $divisionValidator;

	/**
	 * The location repositor implementation.
	 * 
	 * @var Acme\Repositories\Db\LocationRepository
	 */
	protected $locations;

	/**
	 * The location validator implementation.
	 * 
	 * @var Acme\Services\Validation\LocationValidator
	 */
	protected $locationValidator;

	/**
	 * The purchase order repositor implementation.
	 * 
	 * @var Acme\Repositories\Db\PoRepository
	 */
	protected $pos;

	/**
	 * The purchase order validator implementation.
	 * 
	 * @var Acme\Services\Validation\PoValidator
	 */
	protected $poValidator;

	/**
	 * The item validator implementation.
	 * 
	 * @var Acme\Services\Validation\ItemValidator
	 */
	protected $itemValidator;

	/**
	 * Create new PoController instance.
	 * 
	 * @param Po $pos 
	 * @return void 
	 */
	public function __construct( 
		Division $divisions, 
		DivisionValidator $divisionValidator,
		Item $items,
		ItemValidator $itemValidator,
		Location $locations, 
		LocationValidator $locationValidator, 
		Po $pos, 
		PoValidator $poValidator 
	){
		// Implementations.
		// 
		$this->divisions            = $divisions;
		$this->divisionValidator    = $divisionValidator;
		$this->items            	= $items;
		$this->itemValidator        = $itemValidator;
		$this->locations            = $locations;
		$this->locationValidator    = $locationValidator;
		$this->pos                  = $pos;
		$this->poValidator          = $poValidator;
		
		// Execute BaseController::__construct();
		// 
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Capture any search criteria from the query string.
		// 
		$q = Input::query('q', null);

		// Fetch the session user's purchase orders in paginated form.
		// 
		$pos = $q 
			? $this->pos->searchMyPosPaginated( $q , 5, array('attachments', 'items'))
			: $this->pos->myPos( true , 5, array('attachments', 'items'));

		// Count the number of purchase order records that are pending accounting's approval.
		// 
		$requireAccountingApprovalCount = $this->pos->pendingAccountingApproval()->count();

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('pos.index')
									->with('requireAccountingApprovalCount', $requireAccountingApprovalCount)
									->withPos(new PaginatedPresenterCollection('PoPresenter', $pos))
									->withQuery($q);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// Fetch all available divisions.
		// 
		$divisions = $this->divisions->all();

		// Fetch all available ship to locations.
		// 
		$locations = $this->locations->all();

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('pos.create');

		// Nest a form inside of the content.
		// 
		$this->layout->content->nest('form', 'pos.create.form', compact('divisions', 'locations'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Check if the user was wanting to be forwarded to the attachments page.
		// 
		$forward_to_attachments = Input::get('forward_to_attachments', false);

		/***************************************************
		 * First, we will validate the portions of the po
		 * except for line items.
		 *------------------------
		 * If that passes validation we then proceed to 
		 * validate line items.
		 *------------------------
		 * If everything check outs we will create a new
		 * purchase order record.
		 ***************************************************/

		// Capture the postback data.
		// 
		$input = Input::except('forward_to_attachments');

		// Error container. 
		// 
		$errors = array();

		// The submitted po data did not pass validation.
		// 
		if( ! $this->poValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$poErrors = $this->poValidator->errors();

			// Errors Exist
			// 
			if( count( $poErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $poErrors->getMessages());
			}
		}

		// The submitted item data did not pass validation.
		// 
		if( ! $this->itemValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$itemErrors = $this->itemValidator->errors();

			// Errors Exist
			// 
			if( count( $itemErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $itemErrors->getMessages());
			}
		}

		// The submitted division data did not pass validation.
		// 
		if( ! $this->divisionValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$divisionErrors = $this->divisionValidator->errors();

			// Errors Exist
			// 
			if( count( $divisionErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $divisionErrors->getMessages());
			}
		}

		// The submitted location data did not pass validation.
		// 
		if( ! $this->locationValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$locationErrors = $this->locationValidator->errors();

			// Errors Exist
			// 
			if( count( $locationErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $locationErrors->getMessages());
			}
		}

		// Count the number of error messages.
		// 
		$errorCount = count($errors);

		// Create new purchase order record if there are
		// no errors.
		// 
		if( ! $errorCount)
		{
			// Generate new purchase order record.
			// 
			$listing = $this->pos->create( $input );

			// Flash a message that tells the user their po was successfully submitted.
			// 
			Session::flash('po_successful', 'Your purchase order has been submitted.');
			
			// Redirect the user back to their PO list page.
			// 
			return $forward_to_attachments
						? Redirect::route('pos.attachments.index', $listing->getPoIdentifier())
							: Redirect::route('pos.index');
		}

		// Go back to the form and display error messages.
		// 
		return $this->redirectBackWithErrors($errors);
	}

	/** 
	 * Download specified resource as pdf.
	 * 
	 * @param  int  $id
	 * @return Response
	 */
	public function pdf($id)
	{
		// Fetch the purchase order record.
		// 
		$po = $this->pos->find($id);

		// Obtain the items that belong to the po.
		// 
		$items = $po->items;

		// Bail if the purchase order does not exist.
		// 
		if(  is_null($po) ) return App::abort(404, "The purchase order you're attempting to view has been removed or does not exist.");

		// Bail if the user is not allowed to access the page.
		// 
		if( ! $po->isAllowedUser() ) return App::abort(403, 'You are not allowed to access this page.');

		// Fetch all available divisions.
		// 
		$divisions = $this->divisions->all();

		// Capture just the ids of the selected divisions.
		// 
		$selectedDivisions = array_map(function($record){
			return $record['id'];
		}, $po->divisions->toArray());

		// Fetch all available ship to locations.
		// 
		$locations = $this->locations->all();

		// Capture just the ids of the selected locations.
		// 
		$selectedLocations = array_map(function($record){
			return $record['id'];
		}, $po->locations->toArray());

		// Convert the PO object to a presenter object.
		// 
		$po = new PoPresenter($po);

		// Obtain the HTML from the view.
		// 
		$html = View::make('pos.pdf', compact('po', 'divisions', 'items', 'locations', 'selectedDivisions', 'selectedLocations'))->render();

		// Now generate the PDF.
		// 
	    return PDF::load($html, 'A4', 'portrait')->show();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// Fetch the purchase order record.
		// 
		$po = $this->pos->find($id);

		// Bail if the purchase order does not exist.
		// 
		if(  is_null($po) ) return App::abort(404, "The purchase order you're attempting to view has been removed or does not exist.");

		// Bail if the user is not allowed to access the page.
		// 
		if( ! $po->isAllowedUser() ) return App::abort(403, 'You are not allowed to access this page.');

		// Fetch all available divisions.
		// 
		$divisions = $this->divisions->all();

		// Capture just the ids of the selected divisions.
		// 
		$selectedDivisions = array_map(function($record){
			return $record['id'];
		}, $po->divisions->toArray());

		// Fetch all available ship to locations.
		// 
		$locations = $this->locations->all();

		// Capture just the ids of the selected locations.
		// 
		$selectedLocations = array_map(function($record){
			return $record['id'];
		}, $po->locations->toArray());

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('pos.show')->withPo(new PoPresenter($po));

		// Nest a form inside of the content.
		// 
		$this->layout->content->nest('form', 'pos.show.form', compact('po', 'divisions', 'locations', 'selectedDivisions', 'selectedLocations'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// Fetch the purchase order record.
		// 
		$po = $this->pos->find( $id );

		// Bail if the purchase order does not exist.
		// 
		if(  is_null($po) ) return App::abort(404, "The purchase order you're attempting to view has been removed or does not exist.");

		// Bail if the user is not allowed to access the page.
		// 
		if( $po->isApprovedComplete() ) return App::abort(403, 'You can no longer make changes to this record since it has already been approved by management and accounting.');

		// Bail if the user is not allowed to access the page.
		// 
		if( ! $po->isAllowedUser() ) return App::abort(403, 'You are not allowed to access this page.');

		// Fetch all available divisions.
		// 
		$divisions = $this->divisions->all();

		// Capture just the ids of the selected divisions.
		// 
		$selectedDivisions = array_map(function($record){
			return $record['id'];
		}, $po->divisions->toArray());

		// Fetch all available ship to locations.
		// 
		$locations = $this->locations->all();

		// Capture just the ids of the selected locations.
		// 
		$selectedLocations = array_map(function($record){
			return $record['id'];
		}, $po->locations->toArray());

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('pos.edit')->withPo(new PoPresenter($po));

		// Nest a form inside the content.
		// 
		$this->layout->content->nest('form', 'pos.edit.form', compact('po', 'divisions', 'locations', 'selectedDivisions', 'selectedLocations'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// Check if the user was wanting to be forwarded to the attachments page.
		// 
		$forward_to_attachments = Input::get('forward_to_attachments', false);

		/***************************************************
		 * First, we will validate the portions of the po
		 * except for line items.
		 *------------------------
		 * If that passes validation we then proceed to 
		 * validate line items.
		 *------------------------
		 * If everything check outs we will create a new
		 * purchase order record.
		 ***************************************************/

		// Capture the postback data.
		// 
		$input = Input::all();

		$input['po']['items'] = array_map(function($record)
		{
			$record['tax'] = stripCommas($record['tax']);
			$record['total'] = stripCommas($record['total']);

			$record = array_filter($record, 'strlen');

			return $record;

		}, $input['po']['items']);

		// Error container. 
		// 
		$errors = array();

		// The submitted po data did not pass validation.
		// 
		if( ! $this->poValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$poErrors = $this->poValidator->errors();

			// Errors Exist
			// 
			if( count( $poErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $poErrors->getMessages());
			}
		}

		// The submitted item data did not pass validation.
		// 
		if( ! $this->itemValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$itemErrors = $this->itemValidator->errors();

			// Errors Exist
			// 
			if( count( $itemErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $itemErrors->getMessages());
			}
		}

		// The submitted division data did not pass validation.
		// 
		if( ! $this->divisionValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$divisionErrors = $this->divisionValidator->errors();

			// Errors Exist
			// 
			if( count( $divisionErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $divisionErrors->getMessages());
			}
		}

		// The submitted location data did not pass validation.
		// 
		if( ! $this->locationValidator->validate($input))
		{
			// Capture the validation error message(s).
			// 
			$locationErrors = $this->locationValidator->errors();

			// Errors Exist
			// 
			if( count( $locationErrors )) 
			{
				// Merge the errors into the $errors container.
				// 
				$errors = array_merge($errors, $locationErrors->getMessages());
			}
		}

		// Count the number of error messages.
		// 
		$errorCount = count($errors);

		// Create new purchase order record if there are
		// no errors.
		// 
		if( ! $errorCount)
		{
			// Generate new purchase order record.
			// 
			$listing = $this->pos->update( $id , $input );

			// Flash a message that tells the user their po was successfully submitted.
			// 
			Session::flash('po_successful', 'Your purchase order has been updated.');
			
			// Redirect the user back to their PO list page.
			// 
			return $forward_to_attachments
				? Redirect::route('pos.attachments.index', $listing->getPoIdentifier())
					: Redirect::route('pos.index');
		}

		// Go back to the form and display error messages.
		// 
		return $this->redirectBackWithErrors($errors);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return (int) $this->pos->find($id)->delete();
	}
}