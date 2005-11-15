<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Quiet.php';

class ScriptReorganizer_Tests_Strategy_QuietTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function testReformatSuccessful()
    
    public function testReformatSuccessful()
    {
        $os = PHP_EOL == "\r\n" ? '-win' : ( PHP_EOL == "\n" ? '-unix' : '-mac' );
        $content = file_get_contents( 'ScriptReorganizer/Tests/files/sample' . $os . '.php', true );
        $eol = $this->getEolStyle( $content );
        $expected = $eol . '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedQuietedScript.php' ) . '?>' . $eol;
        $strategy = new ScriptReorganizer_Strategy_Quiet;
        
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
