<?php

use Acme\Interfaces\Db\UserRepositoryInterface as User;
use Acme\Services\Validation\LoginValidator as Validator;

class LoginController extends \BaseController {

	/**
	 * The user repository implementation.
	 * 
	 * @var Acme\Repositories\Db\UserRepository
	 */
	protected $users;

	/**
	 * The login validator implementation.
	 * 
	 * @var Acme\Services\Validation\LoginValidator
	 */
	protected $validator;

	/**
	 * List of method names that are allowed to bypass the authentication filter.
	 * 
	 * @var array
	 */
	protected $whitelist = array('index', 'store');

	/**
	 * Create new LoginController instance.
	 *
	 * @return void
	 */
	public function __construct( User $users , Validator $validator )
	{
		$this->users 	 = $users;
		$this->validator = $validator;

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
		// Create a form view.
		// 
		$form = $this->singleView('login.index.form');

		// Render review.
		// 
		$this->view('login.index', compact('form'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
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

		// Attempt to authenticate the user via LDAP.
		// 
		$authenticated = Ldap::authenticate( Input::except('_token') );

		// The user credentials were invalid.
		// 
		if( ! $authenticated )
		{
			// Create a custom error messsage.
			// 
			$errors = array(
				'login_failed' => 'Either the username or password were incorrect.'
			);

			// Send the user back to the form and display error message(s)
			// that communicate that they failed to log in due to invalid
			// credentials.
			// 
			return $this->redirectBackWithErrors( $errors );
		}

		//-------------------------------
		// User has been authenticated.
		//-------------------------------

		// Check and see if the user exists in our database.
		// 
		$user = $this->users->create( (array) Ldap::user() );

		// Log the user into their account.
		// 
		Auth::loginUsingId( $user->id );

		// Send the user to the purchase order list page. If the user is an administrator
		// they will be send to the accounting approvals page.
		// 
		return $this->redirectRoute( Auth::user()->isAdmin() ? 'approvals.index' : 'pos.index' );
	}
}