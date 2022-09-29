<?php

// MIT License, Copyright 2022 Nathan Wooten, https://github.com/nathanwooten/Autoloader/blob/main/LICENSE.md

namespace nathanwooten;

use Exception;

if ( ! class_exists( 'nathanwooten\Autoloader' ) ) {
class Autoloader {

  // Package properties, constants for consistency
  const NAMESPACE = 'namespace';
  const DIRECTORY = 'directory';

  const PROPERTIES = 'properties';
    const EXTENSION = 'extension';
    const NAME = 'name';
    const NORMALIZE = 'normalize';
    const RESULT = 'result';

  protected static $property_list = [
    self::NAME,
    self::RESULT,
    self::NORMALIZE,
    self::EXTENSION
  ];

  protected static bool $normalize = true;

  // Is/is-not registered, automatic on set

  protected static bool $registered = false;

  // Packages can be set freely, as processing is lazy, please include at least a namespace and directory

  public static array $packages = [];

  public static function autoload( $config = null )
  {

    $config = static::config( $config );
    $autoloads = [];

    $label_directory = static::DIRECTORY;

    foreach( $config as $vendor_path => $packages ) {

      $vendor_path = static::doNormalize( $vendor_path );

      foreach ( $packages as $package ) {
        if ( ! is_array( $package ) ) {
          throw new Exception( 'A package must be an array: ' . gettype( $package ) . ' given' );
        }

        $packaged = static::package( $package );

        if ( ! isset( $packaged[ $label_directory ] ) ) {
          $packaged[ $label_directory ] = '';
        }

        $directory = $vendor_path . DIRECTORY_SEPARATOR . static::doNormalize( $packaged[ $label_directory ], 'l' );
		$packaged = static::withParam( $packaged, $label_directory, $directory );

        $index = static::set( $packaged );

        $autoloads[ $index ] = static::get( $index );
      }
    }

    return $autoloads;

  }

  public static function config( $config )
  {

    if ( ! is_array( $config ) ) {
      if ( ! is_string( $config ) ) {
        throw new Exception( 'If config is not an array it must be a readable string, ' . gettype( $config ) . ' provided' );
      }
      $file = $config;
      if ( ! file_exists( $file ) ) {
        throw new Exception( 'Config file does not exist: ' . $file );
      }
      $config = include $file;
    }

    if ( ! is_array( $config ) ) {
      throw new Exception( 'Config must evaluate to an array' );
    }

    foreach ( $config as $vendor_path => $packages ) {
		
      if ( ! is_readable( $vendor_path ) ) {
        throw new Exception( 'All vendor paths (config keys), must be readable, given: ' . $vendor_path );
      }

      if ( ! is_array( $packages ) ) {
        throw new Exception( 'Packages array must be and array, given: ' . gettype( $packages ) );
      }

	  foreach ( $packages as $index => $package ) {
        if ( ! is_array( $package ) || empty( $package ) ) {
          throw new Exception( 'A package must be an array and must not be empty, given: ' . gettype( $package ) );
        }
      }
    }

    return $config;

  }

  // Add a package by parameter, including $namespace, $directory and $properties;

  public static function add( $namespace, $directory, $properties = [] )
  {

    return static::set( static::package( func_get_args() ) );

  }

  // Set a package, requires a name property

  public static function set( $package )
  {

    // Check register always
    if ( ! static::$registered ) {
      // Register once
      spl_autoload_register( 'nathanwooten\Autoloader::load', true, true );
      static::$registered = true;
    }

    $label_namespace = static::NAMESPACE;
    $label_directory = static::DIRECTORY;

    $label_properties = static::PROPERTIES;
      $label_property_name = static::NAME;
      $label_property_normalize = static::NORMALIZE;

    // Return and properties
    $index = null;
    $has = 0;

    // Requires name
    if ( ! array_key_exists( $label_namespace, $package ) ) {
      throw new Exception( 'Provided package array must have a ' . $label_namespace . ' key' . ( isset( $package[ $label_directory ] ) ? ' ' . $package[ $label_directory ] : '' ) );
    }

    $index = static::has( $package[ $label_namespace ] );
    if ( $index || 0 === $index ) {
      return $index;
    }

   // Directory process
    if ( ! isset( $package[ $label_directory ] ) ) {
      $package[ $label_directory ] = '';
    }

    $name = $package[ $label_namespace ];
    $dir = $package[ $label_directory ];

    // Check name match
    foreach ( static::$packages as $index => $pkg ) {

      if ( ( isset( $pkg[ $label_namespace ] ) || empty( $pkg[ $label_namespace ] ) ) &&
        $name === $pkg[ $label_namespace ] &&
        $dir === $pkg[ $label_directory ]
      ) {
        $has = 1;
        break;
      }
    }

    if ( ! isset( $package[ $label_properties ][ $label_property_name ] ) ) {
      $package = static::withProperty( $package, $label_property_name, $package[ $label_namespace ] );
    }

    if ( ! isset( $package[ $label_properties ][ $label_property_normalize ] ) ) {
      $package = static::withProperty( $package, $label_property_normalize, static::normalize() );
    }

    // If no match, 
    if ( ! $has ) {
      $index = count( static::$packages );
    }

    // Set it
    static::$packages[ $index ] = $package;

    // Return index
    return $index;

  }

  // Get package, by value, from the packages array

  public static function get( $id )
  {

    $package = false;

    $packages = static::$packages;
    $label_properties = static::PROPERTIES;

    if ( array_key_exists( $id, $packages ) ) {
      $package = static::$packages[ $id ];

    } else {

      if ( false === $package ) {
	    foreach ( $packages as $key => $pkg ) {
          if ( in_array( $id, $pkg ) ) {
            $package = $pkg;
          }
        }
      }

      if ( false === $package ) {
        foreach ( $packages as $key => $pkg ) {
          if ( in_array( $id, $package[ $label_properties ] ) ) {
            $package = $pkg;
          }
        }
      }
    }

    return $package;

  }

  // Has property in the packages array

  public static function has( $namespace )
  {

    foreach ( static::$packages as $package ) {
      if ( $namespace === $package[ static::NAMESPACE ] ) {
        return true;
      }
    }

  }

  // Add a property to an existing package

  public static function addProperty( $id, $property, $value )
  {

    $label_properties = static::PROPERTIES;

    // If it doesn't get, it doesn't add
    $package = static::get( $id );
    if ( $package ) {

        // With then set
		$package = static::withProperty( $package, $property, $value );
		return static::set( $package );
    }

  }

  // Get a property from a package

  public static function getProperty( $id, $property )
  {

    $label_properties = static::PROPERTIES;

    // Get then check properties array
    $package = static::get( $id );
	if ( $package && isset( $package[ $label_properties ][ $property ] ) ) {
      return $package[ $label_properties ][ $property ];

    }

  }

  // Remove a property from an existing package

  public static function removeProperty( $id, $property )
  {

	// Get the properties label
	$label_properties = static::PROPERTIES;

    // Must be a package to remove from
    $package = static::get( $id );
    if ( $package ) {
      // Unsets it whether it exists or not
      unset( $package[ $label_properties ][ $property ] );

      // Re-set
      static::set( $package );
    }

  }

  public static function withParam( $package, $param_name, $value, $is = 0 )
  {

	$list = ( 0 === $is ? static::getParamList() : static::getPropertyList() );

    foreach ( $package as $key => $v ) {
      if ( $key === $param_name || $key === array_search( $param_name, $list ) ) {
        $package[ $key ] = $value;
      }
    }

    return $package;

  }

  public static function withProperty( $properties, $property_name, $value )
  {

    return static::withParam( $properties, $property_name, $value, 1 );

  }

  // Unpackage and array

  public static function unpackage( array $associative )
  {

    // Since we can't return multiple values we simple un-assoc the array
    $unpackaged = array_values( $assoc );
    if ( isset( $unpackaged[ 2 ] ) && is_array( $unpackaged[ 2 ] ) ) {
      $unpackaged[ 2 ] = static::unpackage( $unpackaged[ 2 ] );
    }

    return $unpackaged;

  }

  // Package a set of properties

  public static function package( array $params, int $keys = 0 )
  {

    $package = [];

    switch( $keys ) {
      case 0:
		$list = 'getParamList';

        break;
      case 1:
        $list = 'getPropertyList';

        break;
    }

	$param_list = static::$list();

    if ( count( $params ) < count( $param_list ) ) {
      $slice = 'param_list';
      $count = count( $params );

    } elseif ( count( $param_list ) < count( $params ) ) {
      $slice = 'params';
      $count = count( $param_list );

    }

    if ( isset( $slice ) ) {
      // Var vars come in handy
      $$slice = array_slice( $$slice, 0, $count );
    }

    // Combine parameter list with parameter values
    $package = array_combine( $param_list, $params );

    if ( 'getParamList' === $list ) {
      if ( isset( $package[ static::PROPERTIES ] ) ) {
		$package[ static::PROPERTIES ] = static::package( $package[ static::PROPERTIES ], 1 );
      }
	}

    // Return packaged
    return $package;

  }

  // Get normalize option value

  public static function normalize( bool $normalize = null)
  {

    // If provided set it
    if ( isset( $normalize ) ) {
      static::$normalize = $normalize;
    }

    // Return property
    return static::$normalize;

  }

  protected static function getParamList()
  {

	return [ static::NAMESPACE, static::DIRECTORY, static::PROPERTIES ];

  }

  // Set your own properties list

  protected static function setPropertyList( array $property_list )
  {

    $property_list = array_values( $property_list );

    static::$property_list = $property_list;

  }

  public static function getPropertyList()
  {

    return static::$property_list;

  }

  // Is property or is param, returns int 0/1

  public static function is( $var_name )
  {

    return in_array( $var_name, static::getParamList() ) ? 0 : 1;

  }

  // Loads an interface, class or trait, or file
  // Called automatically or manually in the case of loading files

  public static function load( $interface )
  {

    $result = null;

    $label_namespace = static::NAMESPACE;
    $label_directory = static::DIRECTORY;

	$label_properties = static::PROPERTIES;
      $label_property_extension = static::EXTENSION;
      $label_property_normalize = static::NORMALIZE;
      $label_property_result = static::RESULT;

    // Loop through available packages in the singleton
    foreach ( static::$packages as $index => $package ) {
      if ( ! isset( $package[ $label_namespace ] ) || ! isset( $package[ $label_directory ] ) ) {
        continue;
      }

      // Required by the autoloader to load a package
      $namespace = $package[ $label_namespace ];
      $directory = $package[ $label_directory ];

      // If needs normalizing...
      if ( static::normalize(
        isset( $package[ $label_properties ][ $label_property_normalize ] ) ? $package[ $label_properties ][ $label_property_normalize ] : null
      ) ) {
		$namespace = static::doNormalize( $namespace );
        $directory = static::doNormalize( $directory );
      }

      $interface = static::doNormalize( $interface );
      $extension = isset( $package[ $label_properties ][ $label_property_extension ] ) ? '.' . $package[ $label_properties ][ $label_property_extension ] : '.' . 'php';

      $file = str_replace( $namespace, $directory, $interface ) . $extension;

      if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
        $file = $directory . DIRECTORY_SEPARATOR . $interface . $extension;

        if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
          continue;
        }
      }

      // We only include once, because we are loading interfaces, and therefore check included files
      if ( ! in_array( $file, get_included_files() ) ) {
        $result = require $file;

         // Set the package result
        static::addProperty( $namespace, $label_property_result, $result );

        break;
      }
    }


    // Null or require result
    return $result;

  }

  protected static function doNormalize( $item, $side = 'r' )
  {

    $fn = $side . 'trim';

    // True trim
    $normalized = $fn( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $item ), DIRECTORY_SEPARATOR );
  
    return $normalized;

  }

}
}