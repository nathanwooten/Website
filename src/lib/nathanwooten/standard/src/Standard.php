<?php

namespace nathanwooten\Standard;

if ( ! defined( 'LIB_PATH' ) ) define( 'LIB_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

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
          'nathanwooten\Standard',
          'standard' . DS . 'src'
        ],
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
