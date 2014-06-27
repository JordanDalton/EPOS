<?php

HTML::macro('alert', function($type, $message, $head = null)
{
	switch( $type )
	{
		case 'danger': //red
			$head = $head ? $head : 'Error';
				break;
		case 'warning': //yellow
			$head = $head ? $head : 'Warning';
				break;
		case 'info': //blue
			$head = $head ? $head : 'Info';
				break;
		case 'success': //green
			$head = $head ? $head : 'Success';
				break;
	}
	return '<div class="alert alert-'. $type .'"><strong>'. $head .' </strong>' . $message . '</div>';
});