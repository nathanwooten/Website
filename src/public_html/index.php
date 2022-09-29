<?php

$container = require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

// App...

var_dump( $container );

$uri = $container->get( nathanwooten\Uri\Uri::class );
var_dump( $uri );
