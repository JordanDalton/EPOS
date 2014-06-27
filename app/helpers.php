<?php

if ( ! function_exists('filterKeywords'))
{
	/** 
	 * Take an array of words and filter out the most commonly used words.
	 * 
	 * @param  array $keywords The list of keywords that have been extracted from a string.
	 * @return [type]           The filtered list of keywords.
	 */
	function filterKeywords( array $keywords = array() )
	{
		return array_diff( $keywords , commonWords() );
	}
}

if ( ! function_exists('commonWords'))
{
	/** 
	 * List of the most commonly used words. (Gathered from Wikipedia).
	 * 
	 * @return array
	 */
	function commonWords()
	{
		return array(
			'a', 'about', 'after', 'all', 'also', 'an', 'and', 'any', 'as', 'at', 
			'back', 'be', 'because', 'but', 'by', 
			'can', 'come', 'could', 
			'day', 'do', 
			'even', 
			'first', 'for', 'from', 
			'get', 'give', 'go', 'good', 
			'have', 'he', 'her', 'him', 'his', 'how', 
			'I', 'if', 'in', 'into', 'it', 'its', 
			'just', 
			'like', 'look', 
			'know', 
			'make', 'me', 'most', 'my', 
			'new', 'no', 'not', 'now', 
			'of', 'on', 'one', 'only', 'or', 'other', 'our', 'out', 'over', 
			'people', 
			'say', 'see', 'she', 'so', 'some', 
			'take', 'than', 'that', 'the', 'their', 'them', 'then', 'there', 'these', 'they', 'this', 'think', 'time', 'to', 'two', 
			'up', 'us', 'use', 
			'want', 'way', 'we', 'well', 'what', 'when', 'which', 'who', 'will', 'with', 'work', 'would', 
			'year', 'you', 'your',  
		);
	}
}


if ( ! function_exists('camelCasedRouteName'))
{
	/**
	 * Generate a URL to a controller action.
	 *
	 * @param  string  $name
	 * @param  bool    $absolute
	 * @return string
	 */
	function camelCasedRouteName( $name = false )
	{
		// Capture the current route
		// 
		$current_route_name = $name ? $name : Route::currentRouteName();

		// Replace all dots with underscores
		// 
		$replace_dots = str_replace('.', '_', $current_route_name);

		// Return the camel cased version
		// 
		return camel_case( $replace_dots );
	}
}


if ( ! function_exists('extractLineItemNumber'))
{
	/**
	 * Generate a URL to a controller action.
	 *
	 * @param  string  $string
	 * @return string
	 */
	function extractLineItemNumber( $string = '' )
	{
		return preg_replace( '/(.*)\#(\d+)(.*)/' , '$2' , $string );
	}
}


if ( ! function_exists('myUnset'))
{
	/**
	 * Unset a given variable.
	 *
	 * @param  mixed  $string
	 * @return void
	 */
	function myUnset( $target )
	{
		$target = null;

		unset( $target );
	}
}


if ( ! function_exists('keyMatchCounter'))
{
	/**
	 * Count the number of array keys that matcha particular regex pattern.
	 * 
	 * @param  string $regexPattern Regular expression pattern.
	 * @param  array  $array        The array to analyse.
	 * @return integer              The number of matches.
	 */
	function keyMatchCounter( $regexPattern = '' , $array = array() )
	{
		// Return all the keys or a subset of the keys of an array
		// 
		$array_keys = array_keys( $array );

		// Return array entries that match the pattern
		// 
		$keyMatches = preg_grep( "/{$regexPattern}/" , $array_keys );

		// Return the number of of $keyMatches.
		// 
		return count( $keyMatches );
	}
}


if ( ! function_exists('set_error'))
{
	/**
	* Add has-error to form-group.
	* 
	* @param string $key key/name of input field being checked
	* @param object $errors just passing the global $errors variable to the function
	*/
	function set_error($key, $errors)
	{
		return $errors->has($key) ? 'has-error' : '';
	}
}


if ( ! function_exists('set_panel_error'))
{
	/**
	* Add has-error to form-group.
	* 
	* @param string $key key/name of input field being checked
	* @param object $errors just passing the global $errors variable to the function
	*/
	function set_panel_error($key, $errors)
	{
		return $errors->has($key) ? 'panel-danger' : '';
	}
}
 

 if ( ! function_exists('get_error'))
{
	/**
	* Get error message and add to a help-block.
	* 
	* @param string $key key/name of input field being checked
	* @param object $errors just passing the global $errors variable to the function
	*/
	function get_error($key, $errors)
	{
		return $errors->has($key) ? $errors->first($key, '<span class="help-block">:message</span>'): '';
	}
}


if ( ! function_exists('get_error_alert'))
{
	/**
	* Get error message and add to a help-block.
	* 
	* @param string $key key/name of input field being checked
	* @param object $errors just passing the global $errors variable to the function
	*/
	function get_error_alert($key, $errors, $type = 'danger')
	{
		$error = get_error($key, $errors);

		if( $error ) return '<div class="alert alert-'.$type.'" style="padding:0 15px"><strong>' . $error . '</strong></div>';
	}
}


if ( ! function_exists('addOrdinalNumberSuffix'))
{
	/**
	 * Convert number to ordinal number.
	 * @param int $number.
	 * @return ordinal number.
	 */
	function addOrdinalNumberSuffix( $number )
	{
		if( ! in_array(($number % 100),array(11,12,13)))
		{
			switch( $number % 10 )
			{
				// Handle 1st, 2nd, 3rd
				case 1:  return $number.'st';
				case 2:  return $number.'nd';
				case 3:  return $number.'rd';
			}
		}
		return $number.'th';
	}
}

if ( ! function_exists('stripCommas'))
{
	function stripCommas($str)
		{
			return preg_replace('#(?<=\d),(?=\d)#','',$str);
		}
}