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
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

class ScriptReorganizer_Tests_Type_Decorator_AddFooterTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $this->footer = PHP_EOL . PHP_EOL . '/* FOOTER */';
        
        $os = PHP_EOL == "\r\n" ? '-win' : ( PHP_EOL == "\n" ? '-unix' : '-mac' );
        $rp = realpath( dirname( __FILE__ ) . '/../../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'sample' . $os . '.php';
        $this->target = $rp . 'footerScript.php';
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->footer );
        unset( $this->source );
        
        if ( is_file( $this->target ) ) {
            unlink( $this->target );
            clearstatcache();
        }
        
        unset( $this->target );
    }
    
    // }}}
    
    // {{{ public function testDefaultFooterAdded()
    
    public function testDefaultFooterAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . $this->footer
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) ), $this->footer
        );
        
        $this->footer = null;
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testOverridingFooterAdded()
    
    public function testOverridingFooterAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . $this->footer
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) ), 'FOOTER'
        );
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testFootersEolConverted()
    
    public function testFootersEolConverted()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' ) . $this->footer
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) )
        );
        
        $this->footer = str_replace( PHP_EOL, ( PHP_EOL == "\r\n" ? "\n" : "\r\n" ), $this->footer );
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testFooterNotAdded()
    
    public function testFooterNotAdded()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL
            . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) )
        );
        
        $this->footer = null;
        
        $this->xRescript( $decorator, $expected );
    }
    
    // }}}
    // {{{ public function testFooterStringException()
    
    public function testFooterStringException()
    {
        $expected = 'will not be used';
        
        $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
        );
        
        $this->footer = array( $this->footer );
        
        try {
            $this->xRescript( $decorator, $expected );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'AddFooter-Decorator', $e->getMessage() );
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
            $decorator = new ScriptReorganizer_Type_Decorator_AddFooter(
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
        $decorator->reformat( $this->footer );
        $decorator->save( $this->target );
        
        $this->assertTrue( $expected === file_get_contents( $this->target ) );
    }
    
    // }}}
    
    // {{{ private properties
    
    private $footer = '';
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
