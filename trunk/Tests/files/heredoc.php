<?php

/*
 * http://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
 *
 * general rule: It is very important to note that the line with the closing identifier
 * contains no other characters, except possibly a semicolon (;). That means especially
 * that the identifier may not be indented, and there may not be any spaces or tabs after
 * or before the semicolon. It's also important to realize that the first character before
 * the closing identifier must be a newline as defined by your operating system.
 */

// semicolon can be on the same line or anywhere else, starting from the next line

$hd1 = <<< HD
HD  HereDoc
HD;

// HD ; is not valid

    $hd2 = <<< HD
    HereDoc
HD

;

// right parenthesis can be anywhere, but starting from the next line

// due to the following closing identifier not being at the beginning of the line
// this file does not validate syntax-wise; only for testing purposes!

$hd3 = str_replace( 'HereDoc', 'HEREDOC', <<< HD
        HereDoc
    HD

        );

// HD); or HD ); is not valid

// comma can be anywhere, but starting from the next line

$hd4 = array(
    'Here' => 'Here',
    'HereDoc' => <<< HD
    HereDoc
HD
    
    ,
    'Doc' => 'Doc'
);

// HD, or HD , is not valid

echo $hd1 . PHP_EOL . $hd2 . PHP_EOL . $hd3 . PHP_EOL . $hd4['HereDoc'];

?>
