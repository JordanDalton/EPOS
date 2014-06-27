<?php

class BaseController extends Controller {

	/**
	 * The layout used by the controller.
	 *
	 * @var \Illuminate\View\View
	 */
	protected $layout = 'layouts.default';

	/**
	 * List of method names that are allowed to bypass the authentication filter.
	 * 
	 * @var array
	 */
	protected $whitelist = array();

	/**
	 * Create new BaseController instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Check if the user is logged in.
		// 
		$this->beforeFilter( 'auth' , array( 'except' => $this->whitelist ) );
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

 	/**
 	 * Creates a view
 	 * @param String $path path to the view file
 	 * @param Array $data all the data
 	 * @return void
 	 */
	protected function singleView($path, array $data = array())
	{
		return View::make($path, $data);
	}

 	/**
 	 * Creates a view
 	 * @param String $path path to the view file
 	 * @param Array $data all the data
 	 * @return void
 	 */
	protected function view($path, array $data = array())
	{
		$this->layout->content = View::make($path, $data);
	}

	/**
	 * Redirect back with input and provided data.
	 *
	 * @param array $data All the data.
	 * @return void
	 */
	protected function redirectBack( $data = array() )
	{
		return Redirect::back()->withInput()->with($data);
	}

	/**
	 * Redirect back with input and provided data.
	 *
	 * @param array $errors All of the errors.
	 * @return void
	 */
	protected function redirectBackWithErrors( $errors = array() )
	{
		return Redirect::back()->withInput()->withErrors($errors);
	}

	/**
	 * Redirect to the previous url.
	 *
	 * @return void
	 */
	public function redirectReferer()
	{
		$referer = Request::server('HTTP_REFERER');

		return Redirect::to($referer);
	}

	/**
	 * Redirect to a given route, with optional data.
	 * 
	 * @param  string $route The route name
	 * @param  array  $data  Optional Data
	 * @return void        
	 */
	protected function redirectRoute( $route , $data = array() )
	{
		return Redirect::route( $route , $data );
	}

}