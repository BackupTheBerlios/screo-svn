<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/TestSuite.php';

class ScriptReorganizer_Tests_Type_Decorator_AllTests
{
    // {{{ public static function suite()
    
    public static function suite()
    {
        $suite = new PHPUnit2_Framework_TestSuite;
        
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/AddFooterTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/AddHeaderTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/AddHeaderAndFooterTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/BcompileTest.php' );
        $suite->addTestFile( 'ScriptReorganizer/Tests/Type/Decorator/PharizeTest.php' );
        
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
