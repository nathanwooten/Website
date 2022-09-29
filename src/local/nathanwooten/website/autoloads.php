<?php

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! defined( 'PROJECT_DEPENDENCIES' ) ) {
  $dependencies = [];

  $project_path = PROJECT_PATH . 'local' . DS . PROJECT_NAME . DS;

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

  PROJECT_PATH . 'local' => [

    [
      'nathanwooten\Standard',
      'nathanwooten' . DS . 'standard' . DS . 'src'
    ],

    [
      'nathanwooten\Container',
      'nathanwooten' . DS . 'container' . DS . 'src'
    ],

  ],

  PROJECT_PATH . 'local' . DS . PROJECT_NAME => [

    ...PROJECT_DEPENDENCIES

  ]

] );

if ( ! isset( $autoloads ) ) {
  $autoloads = DEPENDENCIES;
}

return $autoloads;
