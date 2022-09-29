<?php

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! defined( 'DEPENDENCIES' ) ) define( 'DEPENDENCIES', [

  LIB_PATH => [

    [
      'nathanwooten\Standard',
      'nathanwooten' . DS . 'standard' . DS . 'src'
    ],

  ]

] );

if ( ! isset( $autoloads ) ) {
  $autoloads = DEPENDENCIES;
}

return $autoloads;
