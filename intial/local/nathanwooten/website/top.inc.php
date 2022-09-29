<?php

////////// /local/websiteproject/top.inc

use nathanwooten\{

  Autoloader

};

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );
ini_set( 'display_errors', DEBUG );
if ( ! function_exists( 'handleError' ) ) {
function handleError( Exception $e ) {

	$fileAndLine = ( isset( $e->errfile ) ? ', ' . $e->errfile . '::' . $e->errline : '' );
	$message = $e->getMessage() . $fileAndLine;

	error_log( $message );

	if ( DEBUG ) {
		die( $message );
	}
	die;
}
}

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'autoloader' . DS . 'src' . DS . 'Autoloader.php';

Autoloader::autoload( require PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'website' . DS . 'autoloads.php' );
