<?php

return PHP_EOL . PHP_EOL . "define( 'LEAF2', 'LEAF'); define( 'LEAF1', 'LEAF' ); \$heredoc = <<< HEREDOC" . PHP_EOL
    . PHP_EOL . "This is a" . PHP_EOL . "	" . PHP_EOL . "	Heredoc" . PHP_EOL . "		string." . PHP_EOL . "        "
    . PHP_EOL . "HEREDOC;" . PHP_EOL . "define( 'LEAF', 'LEAF' ); echo LEAF;"
    . " define( 'SUB_LEAF', 'SUB_LEAF'); include 'ScriptReorganizer/Tests/files/' . 'tree/subLeaf.php'; define( 'ROOT', 'ROOT' );" 
    . PHP_EOL . PHP_EOL;

?>