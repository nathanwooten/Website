<?php

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! defined( 'PROJECT_DEPENDENCIES' ) ) {
  $dependencies = [];

  $project_path = LIB_PATH . PROJECT_NAME . DS;

  $scan = scandir( $project_path );

  foreach( $scan as $dir ) {
    if ( ! is_dir( $project_path . $dir ) ) {
      continue;
    }
	$directory = $dir . DS . 'src';

    if ( is_dir( $project_path . $directory ) ) {
      $namespace = PROJECT_NAME . '\\' . ucfirst( $dir );

      $dependencies[] = [ $namespace, $directory ];
    }
  }

  define( 'PROJECT_DEPENDENCIES', $dependencies );
}

if ( ! defined( 'DEPENDENCIES' ) ) define( 'DEPENDENCIES', [

  LIB_PATH => [

    [
      'nathanwooten\Standard',
      'nathanwooten' . DS . 'standard' . DS . 'src'
    ],

  ],

  LIB_PATH . PROJECT_NAME => [

    ...PROJECT_DEPENDENCIES

  ]

] );

if ( ! isset( $autoloads ) ) {
  $autoloads = DEPENDENCIES;
}

return $autoloads;
