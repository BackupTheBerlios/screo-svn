<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Extensions/TestFileLoader.php';

class ScriptReorganizer_Tests_AllTests
{
    // {{{ public static function suite()
    
    public static function suite()
    {
        $suite = new PHPUnit2_Extensions_TestFileLoader;
        
        $suite->addTestFile( 'ScriptReorganizer/Tests/Strategy/AllTests.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/AllTests.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/AllTests.php' );
        
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
