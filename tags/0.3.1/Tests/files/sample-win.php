
<?php

// What's all about comments?

echo 'comment 0';



/* This is only a sample file with the different comment styles available in PHP. */

echo 'comment 1';

/*
   Will this comment be removed by some of the strategies?
 */

echo 'comment 2';

/**
 * And this one?
 */
class EchoIt {
	
	/**
	 * And this one too?
	 */
	
	
	
	public function __construct() {
		// initialization code
	}
	
	public function __destruct() {/**/}
	
	
	// It's getting boring!
	
	
	
	public function echoItNow() { /*
		just to be funky ... ;-) */ echo 'comment 3';
		// What about the next one?
		echo 'comment 4'; // Will I be alone?
	}
}

// end of sample file

?>
