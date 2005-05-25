<?php

return PHP_EOL . PHP_EOL . "define( 'LEAF2', 'LEAF'); define( 'LEAF1', 'LEAF' ); define( 'LEAF', 'LEAF' ); echo LEAF;"
    . " define( 'SUB_LEAF', 'SUB_LEAF'); include 'ScriptReorganizer/Tests/files/' . 'tree/subLeaf.php'; define( 'ROOT', 'ROOT' );" 
    . PHP_EOL . PHP_EOL;

?>