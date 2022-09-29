<?php

namespace websiteproject\Container\Services\nathanwootenuriuri;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Uri\Uri

};

class nathanwootenuriuriservice extends ContainerService
{

  protected $id = Uri::class;
  protected $args = [];

  protected $load = [ 'nathanwooten\Uri', LIB_PATH . DS . 'nathanwooten' . DS . 'uri' . DS . 'src' ];

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

  public function args( $args )
  {

    $args = parent::args( $args );

    if ( empty( $args ) ) {
      $args[0] = $_SERVER[ 'REQUEST_URI' ];
    }

    return $args;

  }

}
