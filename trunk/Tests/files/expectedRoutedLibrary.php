<?php

return PHP_EOL . PHP_EOL . "// Leaf 2" . PHP_EOL . PHP_EOL . "define(	'LEAF2', 'LEAF');" . PHP_EOL . PHP_EOL . "// Leaf 1"
    . PHP_EOL . PHP_EOL . "	define( 'LEAF1', 'LEAF' );" . PHP_EOL . PHP_EOL . "	// Leaf" . PHP_EOL . PHP_EOL
    . "	define( 'LEAF', 'LEAF' );" . PHP_EOL . PHP_EOL . "		echo LEAF;" . PHP_EOL . PHP_EOL
    . "		// Sub Leaf" . PHP_EOL . PHP_EOL . "			define( 'SUB_LEAF', 'SUB_LEAF');" . PHP_EOL . PHP_EOL
    . "	include 'ScriptReorganizer/Tests/files/' . 'tree/subLeaf.php';" . PHP_EOL
    . "		// include 'ScriptReorganizer/Tests/files/tree/subLeaf.php';" . PHP_EOL . PHP_EOL
    . "	/* include 'ScriptReorganizer/Tests/files/tree/subLeaf.php'; */" . PHP_EOL
    . "	// include 'ScriptReorganizer/Tests/files/tree/subLeaf.php';" . PHP_EOL . PHP_EOL
    . "	// Root" . PHP_EOL . PHP_EOL . "define(   'ROOT', 'ROOT' );" . PHP_EOL . PHP_EOL;

?>