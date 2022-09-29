<?php

namespace nathanwooten\Standard;

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

use nathanwooten\{

  Autoloader

};

require_once dirname( dirname( __FILE__ ) ) . DS . 'functions.php';

if ( ! class_exists( 'nathanwooten\Standard\Standard' ) ) {
class Standard implements StandardInterface
{

  public function __construct()
  {

    $this->loadnathanwooten();

  }

  public function loadnathanwooten()
  {

    $autoloads = [
      LIB_PATH . 'nathanwooten' => [
        [
          'nathanwooten\Container',
          'container' . DS . 'src'
        ]
      ]
    ];

    Autoloader::autoload( $autoloads );

  }

}
}
