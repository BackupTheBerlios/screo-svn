<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Script.php';

require_once 'ScriptReorganizer/Type/Decorator/Exception.php';
require_once 'ScriptReorganizer/Type/Decorator/Pharize.php';

class ScriptReorganizer_Tests_Type_Decorator_PharizeTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $this->archive = new ScriptReorganizer_Type_Decorator_Pharize(
            new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack )
        );
        
        $this->path = realpath( dirname( __FILE__ ) . '/../../files' ) . DIRECTORY_SEPARATOR;
        
        $this->target = $this->path . 'packedScriptAndLibrary.phar';
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->archive );
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
            $archive = new ScriptReorganizer_Type_Decorator_Pharize(
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
    // {{{ public function testPharizeArrayException()
    
    public function testPharizeArrayException()
    {
        try {
            $this->archive->loadFiles( array() );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'not of type array', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testPharizeStringException()
    
    public function testPharizeStringException()
    {
        try {
            $this->archive->load( $this->path . 'expectedPackedScript.php', '' );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'not of type string', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testSavingPharUnsuccessful()
    
    public function testSavingPharUnsuccessful()
    {
        try {
            $this->archive->load( $this->path . 'expectedPackedScript.php', 'packedScript.php' );
            $this->archive->save( $this->path . '\!?' );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Decorator_Exception $e ) {
            $this->assertContains( 'is not writable', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testPharCreationSuccessful()
    
    public function testPharCreationSuccessful()
    {
        $files = array(
            $this->path . 'expectedPackedLibrary.php' => 'packedLibrary.php',
            $this->path . 'sample.php' => 'script/packedScript.php',
        );
        
        $this->archive->loadFiles( $files );
        $this->archive->reformat();
        $this->archive->save( $this->target, 'script/packedScript.php' );
        
        $this->assertTrue( true === is_file( $this->target ) );
    }
    
    // }}}
    
    // {{{ private properties
    
    private $archive;
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
