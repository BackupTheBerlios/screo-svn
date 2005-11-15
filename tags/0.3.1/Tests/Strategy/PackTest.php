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
    // {{{ public function setUp()
    
    public function setUp()
    {
        $os = PHP_EOL == "\r\n" ? '-win' : ( PHP_EOL == "\n" ? '-unix' : '-mac' );
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        $source = $rp . 'sample' . $os . '.php';
        
        $this->content = file_get_contents( $source );
        $this->eol = $this->getEolStyle( $this->content );
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->content );
        unset( $this->eol );
    }
    
    // }}}
    
    // {{{ public fundtion testDefaultReformatSuccessful()
    
    public function testDefaultReformatSuccessful()
    {
        $expected = $this->eol . '<?php' . $this->eol
            . ( include 'ScriptReorganizer/Tests/files/expectedDefaultPackedScript.php' ) . $this->eol . '?>' . $this->eol;
        $strategy = new ScriptReorganizer_Strategy_Pack;
        
        $this->assertTrue( $expected === $strategy->reformat( $this->content, $this->eol ) );
    }
    
    // }}}
    // {{{ public function testOneLinerReformatSuccessful()
    
    public function testOneLinerReformatSuccessful()
    {
        $expected = ' <?php ' . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . ' ?> ';
        $strategy = new ScriptReorganizer_Strategy_Pack( true );
        
        $this->assertTrue( $expected === $strategy->reformat( $this->content, $this->eol ) );
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
    
    // {{{ private properties
    
    private $content = '';
    private $eol = '';
    
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
