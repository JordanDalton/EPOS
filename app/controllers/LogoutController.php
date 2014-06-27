<?php

class LogoutController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Log the user out of their account.
		// 
		Auth::logout();

		// Flash a message that tells the user they have successfully logged out.
		// 
		Session::flash('logout_successful', 'You have successfully logged out of EPOS.');

		// Send the user back to the login page.
		// 
		return $this->redirectRoute('login.index');
	}

}