<?php

$container = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

// I know, below
use nathanwooten\{

  Http\Request

};

// App...

$request = $container->get( Request::class );

var_dump( $request );
