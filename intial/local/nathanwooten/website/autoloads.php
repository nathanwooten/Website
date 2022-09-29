<?php

$autoloads = [
  PROJECT_PATH . 'local' => [
    [
      'nathanwooten\Standard',
      'nathanwooten' . DS . 'standard' . DS . 'src'
    ],
    [
      'nathanwooten\Container',
      'nathanwooten' . DS . 'container' . DS . 'src'
    ],
    [
      'websiteproject\Container',
      'websiteproject' . DS . 'src' . DS . 'Container'
    ]
  ]
];

return $autoloads;