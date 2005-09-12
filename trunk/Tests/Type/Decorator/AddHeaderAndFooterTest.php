<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Script.php';

require_once 'ScriptReorganizer/Type/Decorator/AddFooter.php';
require_once 'ScriptReorganizer/Type/Decorator/AddHeader.php';
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

class ScriptReorganizer_Tests_Type_Decorator_AddHeaderAndFooterTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $this->footer = PHP_EOL . PHP_EOL . '/* FOOTER */';
        $this->header = '/* HEADER */' . PHP_EOL . PHP_EOL;
        
        $rp = realpath( dirname( __FILE__ ) . '/../../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'sample.php';
        $this->target = $rp . 'headerAndFooterScript.php';
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->footer );
        unset( $this->header );
        unset( $this->source );
        
        if ( is_file( $this->target ) ) {
            unlink( $this->target );
            clearstatcache();
        }
        
        unset( $this->target );
    }
    
    // }}}
    
    // {{{ public function testHeaderAndFooterAdded()
    
    public function testHeaderAndFooterAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . $this->header . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . $this->footer
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
            new ScriptReorganizer_Type_Decorator_AddFooter(
                new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) ), $this->footer
            )
        );
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type_Decorator $decorator, & $expected )
    
    private function xRescript( ScriptReorganizer_Type_Decorator $decorator, & $expected )
    {
        $decorator->load( $this->source );
        $decorator->reformat( $this->header );
        $decorator->save( $this->target );
        
        $this->assertTrue( $expected === file_get_contents( $this->target ) );
    }
    
    // }}}
    
    // {{{ private properties
    
    private $footer = '';
    private $header = '';
    private $source = '';
    private $target = '';
    
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
