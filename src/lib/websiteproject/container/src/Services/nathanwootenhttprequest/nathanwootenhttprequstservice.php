<?php

namespace websiteproject\Container\Services\nathanwootenhttprequest;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Http\Request,
  Http\Uri

};

class nathanwootenhttprequestservice extends ContainerService
{

  protected $id = Request::class;
  protected array $args = [ Uri::class ];

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

}
