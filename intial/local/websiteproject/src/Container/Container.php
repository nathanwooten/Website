<?php

namespace websiteproject\Container;

use nathanwooten\{

  Container\Container as AbstractContainer

};

class Container extends AbstractContainer {

  public function __construct()
  {

    parent::__construct( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Services' );

  }

  public static function getNamespace()
  {

    return __NAMESPACE__;

  }

}
