<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Script.php';

require_once 'ScriptReorganizer/Type/Decorator/Bcompile.php';
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';
require_once 'ScriptReorganizer/Type/Decorator/Pharize.php';

class ScriptReorganizer_Tests_Type_Decorator_BcompileTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $this->bytecode = new ScriptReorganizer_Type_Decorator_Bcompile(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
        );
        
        $this->path = realpath( dirname( __FILE__ ) . '/../../files' ) . DIRECTORY_SEPARATOR;
        
        $this->target = $this->path . 'bEncodedScript.php';
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->bytecode );
        unset( $this->path );
        
        if ( is_file( $this->target ) ) {
            unlink( $this->target );
            clearstatcache();
        }
        
        unset( $this->target );
    }
    
    // }}}
    
    // {{{ public function testSelfDecorationUnsuccessful()
    
    public function testSelfDecorationUnsuccessful()
    {
        try {
            $bytecode = new ScriptReorganizer_Type_Decorator_Bcompile(
                new ScriptReorganizer_Type_Decorator_Bcompile(
                    new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
                )
            );
            
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'sequencing Bcompile', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testPharizeDecorationUnsuccessful()
    
    public function testPharizeDecorationUnsuccessful()
    {
        try {
            $bytecode = new ScriptReorganizer_Type_Decorator_Bcompile(
                new ScriptReorganizer_Type_Decorator_Pharize(
                    new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
                )
            );
            
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'sequencing Pharize', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testSavingBytecodeUnsuccessful()
    
    public function testSavingBytecodeUnsuccessful()
    {
        try {
            $this->bytecode->load( $this->path . 'sample.php' );
            $this->bytecode->save( $this->path . '\!?' );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'is not writable', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testBytecodeCreationSuccessful()
    
    public function testBytecodeCreationSuccessful()
    {
        $this->bytecode->load( $this->path . 'sample.php' );
        $this->bytecode->save( $this->target );
        
        $this->assertTrue( true === is_file( $this->target ) );
    }
    
    // }}}
    
    // {{{ private properties
    
    private $bytecode;
    private $path;
    private $target;
    
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
