<?php

return "\r\n" . "\r\n" . "define( 'LEAF2', 'LEAF');" . "\r\n" . "define( 'LEAF1', 'LEAF' );" . "\r\n"
    . "\$heredoc = <<< HEREDOC" . "\r\n"
    . "\r\n" . "This is a" . "\r\n" . "	" . "\r\n" . "	Heredoc" . "\r\n" . "		string." . "\r\n" . "        "
    . "\r\n" . "HEREDOC;" . "\r\n"
    . "define( 'LEAF', 'LEAF' );" . "\r\n" . "echo LEAF;" . "\r\n" . "define( 'SUB_LEAF', 'SUB_LEAF');"
    . "\r\n" . "include 'ScriptReorganizer/Tests/files/' . 'tree/subLeaf.php';" . "\r\n" . "define( 'ROOT', 'ROOT' );" 
    . "\r\n" . "\r\n";

?>
