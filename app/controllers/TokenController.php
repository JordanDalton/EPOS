<?php

class TokenController extends BaseController {

	/**
	 * Obtain the current token from the user's session.
	 * 
	 * @return string Token
	 */
	public function index()
	{
		// Prepare our response.
		// 
		$response = array( '_token' => \Session::get('_token') );

		// Return json reponse if being requested via ajax.
		// 
		return Request::ajax() ? \Response::json( $response ) : '';
	}
}