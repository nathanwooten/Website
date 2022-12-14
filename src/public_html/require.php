<?php

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

if ( ! function_exists( 'upFind' ) ) {
function upFind( $directory, array $directoryContains )
{

  $is = [];

  while( $directory ) {

    if ( is_file( $directory ) ) {
      $directory = dirname( $directory ) . DIRECTORY_SEPARATOR;
    } else {
      $directory = rtrim( $directory, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
    }

    foreach ( $directoryContains as $contains ) {
      $item = $directory . $contains;

      if ( is_readable( $item ) ) {
        $is[] = $item;
      }
    }

    if ( count( $is ) === count( $directoryContains ) ) {
      return $directory;
    }

    $parent = dirname( $directory );
    if ( $parent === $directory ) {
      $directory = false;
    } else {
      $directory = $parent;
    }
  }

  return $directory;

}
}

$paths = [
  'PROJECT_PATH' => [ 'public_html' ],
  'LIB_PATH' => [ 'lib' ]
];

$append = [
  'LIB_PATH' => 'lib' . DS
];

foreach ( $paths as $define => $contents ) {
  if ( ! defined( $define ) ) {

    if ( empty( $contents ) ) {
      continue;
    }

    $contents = array_values( $contents );

    $has = upFind( __FILE__, $contents );
    if ( ! $has ) {
      throw new Exception;
    }

    $item = rtrim( $has, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
    if ( array_key_exists( $define, $append ) ) {
      $item .= $append[ $define ];
    }

    define( $define, $item );
  }
}

$topfile = PROJECT_PATH . 'top.inc.php';

return require $topfile;
