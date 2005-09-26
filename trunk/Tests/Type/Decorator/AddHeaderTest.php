<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Script.php';

require_once 'ScriptReorganizer/Type/Decorator/AddHeader.php';
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

class ScriptReorganizer_Tests_Type_Decorator_AddHeaderTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $this->header = '/* HEADER */' . PHP_EOL . PHP_EOL;
        
        $os = PHP_EOL == "\n" ? '-unix' : PHP_EOL == "\r" ? '-mac' : '';
        $rp = realpath( dirname( __FILE__ ) . '/../../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'sample' . $os . '.php';
        $this->target = $rp . 'headerScript.php';
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->header );
        unset( $this->source );
        
        if ( is_file( $this->target ) ) {
            unlink( $this->target );
            clearstatcache();
        }
        
        unset( $this->target );
    }
    
    // }}}
    
    // {{{ public function testDefaultHeaderAdded()
    
    public function testDefaultHeaderAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . $this->header . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) ), $this->header
        );
        
        $this->header = null;
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testOverridingHeaderAdded()
    
    public function testOverridingHeaderAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . $this->header . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) ), 'HEADER'
        );
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testHeaderNotAdded()
    
    public function testHeaderNotAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        $this->header = null;
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) )
        );
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testHeaderStringException()
    
    public function testHeaderStringException()
    {
        $expected = 'will not be used';
        $this->header = array( $this->header );
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
        );
        
        try {
            $this->xRescript( $decorator, $expected );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'AddHeader-Decorator', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testPharizeDecoratorException()
    
    public function testPharizeDecoratorException()
    {
        if ( !class_exists( 'ScriptReorganizer_Type_Decorator_Pharize' ) ) {
            require_once 'ScriptReorganizer/Type/Decorator/Pharize.php';
        }
        
        try {
            $decorator = new ScriptReorganizer_Type_Decorator_AddHeader(
                new ScriptReorganizer_Type_Decorator_Pharize(
                    new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
                )
            );
            
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'Pharize-Decorator', $e->getMessage() );
        }
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
