<?php

////////// /local/websiteproject/top.inc

use nathanwooten\{

  Autoloader

};

use websiteproject\{

  Container\Container

};

if ( ! defined( 'PROJECT_NAME' ) ) die( 'Please define "PROJECT_NAME" in your top.inc.php file: /path/to/project/package/top.inc.php' );

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );

ini_set( 'display_errors', DEBUG );

if ( ! function_exists( 'handleError' ) ) {
function handleError( Exception $e ) {

	$fileAndLine = ( isset( $e->errfile ) ? ', ' . $e->errfile . '::' . $e->errline : '' );
	$message = $e->getMessage() . $fileAndLine;

	error_log( $message );

	if ( ! defined( 'DEBUG' ) || DEBUG ) {
		die( $message );
	}
	die;
}
}

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

require_once PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'autoloader' . DS . 'src' . DS . 'Autoloader.php';

$autoloads = require PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'website' . DS . 'autoloads.php';

$autoloads = Autoloader::autoload( $autoloads );

websiteproject\Registry\Registry::set( 'value', 'value' );

$container = new Container;
return $container;
