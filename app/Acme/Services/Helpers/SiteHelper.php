<?php namespace Acme\Services\Helpers;

use Request;

class SiteHelper {

	/** 
	 * Returns body classes made up by URI segments.
	 *
	 * @return string 
	 */
	public static function bodyClass()
	{
		$body_classes = array();
		$uri_segments = explode( '/', Request::path() );
		$class        = "";

		foreach( $uri_segments as $key => $segment )
		{
			if( is_numeric( $segment ) || empty( $segment ) ) continue;

			$class .= ! empty( $class ) ? "-" . $segment : $segment;

			array_push( $body_classes, $class );
		}

		return ! empty( $body_classes ) ? implode( ' ', $body_classes ) : NULL;
	}

	 /**
	* Returns body id made up by URI segments
	*
	* @return string
	*/
	public static function bodyId()
	{
		$body_id = preg_replace('/\d-/', '', str_replace( '/', '-', Request::path() ) );
		
		return $body_id != '-' ? $body_id : "homepage";
	}
}