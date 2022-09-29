<?php

namespace websiteproject\Container\Services\nathanwootenhttpuri;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Http\Uri

};

class nathanwootenhttpuriservice extends ContainerService
{

  protected $id = Uri::class;
  protected array $args = [];

  protected array $add = [
    [
      'nathanwooten\Http',
      LIB_PATH . DS . 'nathanwooten' . DS . 'http' . DS . 'src'
    ]
  ];

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

  public function args( array $args = null )
  {

    $args = parent::args( $args );

    if ( empty( $args ) ) {
      $args[0] = $_SERVER[ 'REQUEST_URI' ];
    }

    return $args;

  }

}
