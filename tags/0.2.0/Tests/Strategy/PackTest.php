<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

class ScriptReorganizer_Tests_Strategy_PackTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public fundtion testDefaultReformatSuccessful()
    
    public function testDefaultReformatSuccessful()
    {
        $content = file_get_contents( 'ScriptReorganizer/Tests/files/sample.php', true );
        $expected = '<?php' . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedDefaultPackedScript.php' ) . PHP_EOL . '?>';
        $strategy = new ScriptReorganizer_Strategy_Pack;
        
        $this->assertTrue( $expected === $strategy->reformat( $content ) );
    }
    
    // }}}
    // {{{ public function testOneLinerReformatSuccessful()
    
    public function testOneLinerReformatSuccessful()
    {
        $content = file_get_contents( 'ScriptReorganizer/Tests/files/sample.php', true );
        $expected = '<?php ' . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . ' ?>';
        $strategy = new ScriptReorganizer_Strategy_Pack( true );
        
        $this->assertTrue( $expected === $strategy->reformat( $content ) );
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
