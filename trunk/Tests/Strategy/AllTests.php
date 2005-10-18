<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/TestSuite.php';

class ScriptReorganizer_Tests_Strategy_AllTests
{
    // {{{ public static function suite()
    
    public static function suite()
    {
        $suite = new PHPUnit2_Framework_TestSuite;
        
        $suite->addTestFile( 'ScriptReorganizer/Tests/Strategy/RouteTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Strategy/QuietTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Strategy/PackTest.php' );
        
        return $suite;
    }
    
    // }}}
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
