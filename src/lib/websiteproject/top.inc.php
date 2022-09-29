<?php

////////// /path/to/lib/<project_name>/top.inc.php

if ( ! defined( 'PROJECT_NAME' ) ) define( 'PROJECT_NAME', basename( dirname( __FILE__ ) ) );

return require dirname( dirname( __FILE__ ) ) . DS . 'nathanwooten' . DS . 'website' . DS . 'top.inc.php';
