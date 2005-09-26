<?php

return "\r\n" . "\r\n" . "define( 'LEAF2', 'LEAF'); define( 'LEAF1', 'LEAF' ); \$heredoc = <<< HEREDOC" . "\r\n"
    . "\r\n" . "This is a" . "\r\n" . "	" . "\r\n" . "	Heredoc" . "\r\n" . "		string." . "\r\n" . "        "
    . "\r\n" . "HEREDOC;" . "\r\n" . "define( 'LEAF', 'LEAF' ); echo LEAF;"
    . " define( 'SUB_LEAF', 'SUB_LEAF'); include 'ScriptReorganizer/Tests/files/' . 'tree/subLeaf.php'; define( 'ROOT', 'ROOT' );" 
    . "\r\n" . "\r\n";

?>