<?php

namespace websiteproject\Container;

if ( ! defined( 'DS', DIRECTORY_SEPARATOR );

class Dependencies
{

  protected $autoloads = [



  ];

  public $config_functions = [
    [
      PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'website' . DS . 'functions.php'
      [
        'getTarget',
        'urlRelative'
      ]
    ]
  ];

  public $fsNorm = [ null, DS, DS ]

  public function __construct()
  {

    if ( ! empty( $this->config_functions ) ) {
      foreach ( $this->config_functions as $function ) {
        $this->requireFunctionsFile( ...$function );
      }
    }

  }


}
