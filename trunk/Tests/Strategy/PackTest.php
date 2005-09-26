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
        $eol = $this->getEolStyle( $content );
        $expected = $eol . '<?php' . $eol
            . ( include 'ScriptReorganizer/Tests/files/expectedDefaultPackedScript.php' ) . $eol . '?>' . $eol;
        $strategy = new ScriptReorganizer_Strategy_Pack;
        
        $this->assertTrue( $expected === $strategy->reformat( $content, $eol ) );
    }
    
    // }}}
    // {{{ public function testOneLinerReformatSuccessful()
    
    public function testOneLinerReformatSuccessful()
    {
        $content = file_get_contents( 'ScriptReorganizer/Tests/files/sample.php', true );
        $eol = $this->getEolStyle( $content );
        $expected = ' <?php ' . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . ' ?> ';
        $strategy = new ScriptReorganizer_Strategy_Pack( true );
        
        $this->assertTrue( $expected === $strategy->reformat( $content, $eol ) );
    }
    
    // }}}
    
    // {{{ private function getEolStyle( & $content )
    
    private function getEolStyle( & $content )
    {
        foreach ( array( "\r\n", "\n", "\r" ) as $eol ) {
            if ( false !== strpos( $content, $eol ) ) {
                return $eol;
            }
        }
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
