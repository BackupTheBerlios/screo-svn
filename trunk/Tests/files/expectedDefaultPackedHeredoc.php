<?php

return PHP_EOL . PHP_EOL . '$hd1 = <<< HD' . PHP_EOL . 'HD  HereDoc' . PHP_EOL . 'HD;'. PHP_EOL
    . '$hd2 = <<< HD' . PHP_EOL . '    HereDoc' . PHP_EOL . 'HD' . PHP_EOL . ';' . PHP_EOL
    . '$hd3 = str_replace( \'HereDoc\', \'HEREDOC\', <<< HD' . PHP_EOL . '    HereDoc' . PHP_EOL . 'HD' . PHP_EOL . ');' . PHP_EOL
    . '$hd4 = array(' . PHP_EOL . '\'Here\' => \'Here\',' . PHP_EOL . '\'HereDoc\' => <<< HD' . PHP_EOL . '    HereDoc' . PHP_EOL
    . 'HD' . PHP_EOL . ',' . PHP_EOL . '\'Doc\' => \'Doc\'' . PHP_EOL . ');' . PHP_EOL
    . 'echo $hd1 . PHP_EOL . $hd2 . PHP_EOL . $hd3 . PHP_EOL . $hd4[\'HereDoc\'];' . PHP_EOL . PHP_EOL;

?>