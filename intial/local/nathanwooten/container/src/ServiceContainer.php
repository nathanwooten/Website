<?php

namespace nathanwooten\Container;

use nathanwooten\{

  Autoloader

};

use Exception;

abstract class ServiceContainer
{

  use OrDefault;

  protected ContainerInterface $container;

  protected $id = IdClass::class;
  protected $args = [];

  protected $load = [];
  protected $property = [];

  public function __construct( Container $container )
  {

    $this->container = $container;

    $this->load( ...$this->load );
  }

  public function service( ...$args )
  {

    if ( ! isset( $this->service ) ) {

      $service = $this->id;
      $args = $this->args( $args );

      $service = new $service( ...$args );

      if ( $this->isFactory() ) {
        return $service;
      }

      $this->service = $service;
    }

    return $this->service;

  }

  public function args( $args )
  {

	$this->args = $this->orDefault( 'args', $args );
    return $this->args;

  }

  public function load( ...$load )
  {

    $index = Autoloader::add( ...$load );
    if ( is_integer( $index ) ) {
      $package = Autoloader::get( $index );
      return $package;
    }

  }

  public function isFactory()
  {

    if ( isset( $this->property[ 'factory' ] ) && $this->property[ 'factory' ] ) {
      return true;
    }

    return false;

  }

}
