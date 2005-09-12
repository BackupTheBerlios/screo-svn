<?php

include_once 'node/leaf2.php';

include_once "ScriptReorganizer/Tests/files/tree/node/leaf1.php";

require_once (  "leaf.php"  ) ;

include 'doesNotExist.php';

// Root

define( 'ROOT', 'ROOT');

require_once( 'subLeaf.php' );

require_once 'node/doesNotExist.php';

?>