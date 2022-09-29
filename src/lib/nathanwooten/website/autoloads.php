<?php

// LIB_PATH should be defined in the /public_html/*/require.php file

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ );

// Standard is the class that autoloads all the standard dependencies
// especially container, which is the dependency manager

if ( ! defined( 'DEPENDENCIES' ) ) define( 'DEPENDENCIES', [

  LIB_PATH => [

    [
      'nathanwooten\Standard',
      'nathanwooten' . DS . 'standard' . DS . 'src'
    ],

  ]

] );

// Set the autoloads variable if not already set

if ( ! isset( $autoloads ) ) {
  $autoloads = DEPENDENCIES;
}

// Return the autoloads (autoload method) autoloads

return $autoloads;
