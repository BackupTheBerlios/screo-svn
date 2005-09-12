<?php

return PHP_EOL . PHP_EOL . "// What's all about comments?" . PHP_EOL . PHP_EOL . "echo 'comment 0';" . PHP_EOL . PHP_EOL
    . "/* This is only a sample file with the different comment styles available in PHP. */" . PHP_EOL . PHP_EOL
    . "echo 'comment 1';" . PHP_EOL . PHP_EOL . "/*" . PHP_EOL . "   Will this comment be removed by some of the strategies?"
    . PHP_EOL . " */" . PHP_EOL . PHP_EOL . "echo 'comment 2';" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * And this one?"
    . PHP_EOL . " */" . PHP_EOL . "class EchoIt {" . PHP_EOL . PHP_EOL . "	/**" . PHP_EOL . "	 * And this one too?"
    . PHP_EOL . "	 */" . PHP_EOL . PHP_EOL . "	public function __construct() {" . PHP_EOL . "		// initialization code"
    . PHP_EOL . "	}" . PHP_EOL . PHP_EOL . "	public function __destruct() {/**/}" . PHP_EOL . PHP_EOL
    . "	// It's getting boring!" . PHP_EOL . PHP_EOL . "	public function echoItNow() { /*" . PHP_EOL
    . "		just to be funky ... ;-) */ echo 'comment 3';" . PHP_EOL . "		// What about the next one?" . PHP_EOL
    . "		echo 'comment 4'; // Will I be alone?" . PHP_EOL . "	}" . PHP_EOL . "}" . PHP_EOL . PHP_EOL
    . "// end of sample file" . PHP_EOL . PHP_EOL;

?>