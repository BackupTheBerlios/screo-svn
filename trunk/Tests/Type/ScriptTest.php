<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';
require_once 'ScriptReorganizer/Strategy/Quiet.php';
require_once 'ScriptReorganizer/Strategy/Route.php';

require_once 'ScriptReorganizer/Type/Exception.php';
require_once 'ScriptReorganizer/Type/Script.php';

class ScriptReorganizer_Tests_Type_ScriptTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'sample.php';
        $this->target = $rp;
    }
    
    // }}}
    // {{{ public function tearDown
    
    public function tearDown()
    {
        unset( $this->source );
        
        if ( is_file( $this->target ) ) {
            unlink( $this->target );
            clearstatcache();
        }
        
        unset( $this->target );
    }
    
    // }}}
    
    // {{{ public function testRescriptNotExistingFile()
    
    public function testRescriptNotExistingFile()
    {
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'doesNotExist.php';
        
        try {
            $script->load( $this->target );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Exception $e ) {
            $this->assertContains( 'is not readable', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testRescriptNotReadableFile()
    
    public function testRescriptNotReadableFile()
    {
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'lockedSample.php';
        
        // hack, due to Windows deficiency chmod-wise
        
        if ( false === strpos( strtolower( PHP_OS ), 'win' ) ) {
            copy( $this->source, $this->target );
        }
        
        chmod( $this->target, 0100 );
        
        try {
            $script->load( $this->target );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Exception $e ) {
            $this->assertContains( 'is not readable', $e->getMessage() );
        }
        
        chmod( $this->target, 0700 );
    }
    
    // }}}
    // {{{ public function testRescriptNotWritableFile()
    
    public function testRescriptNotWritableFile()
    {
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'lockedSample.php';
        
        copy( $this->source, $this->target );
        chmod( $this->target, 0100 );
        
        try {
            $script->save( $this->target );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Exception $e ) {
            $this->assertContains( 'is not writable', $e->getMessage() );
        }
        
        chmod( $this->target, 0700 );
    }
    
    // }}}
    
    // {{{ public function testDefaultPackToScript()
    
    public function testDefaultPackToScript()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL . ( include 'ScriptReorganizer/Tests/files/expectedDefaultPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'defaultPackedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOneLinerPackToScript()
    
    public function testOneLinerPackToScript()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL . ( include 'ScriptReorganizer/Tests/files/expectedOneLinerPackedScript.php' )
            . PHP_EOL . PHP_EOL . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) );
        $this->target .= 'oneLinerPackedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    
    // {{{ public function testQuietToScript()
    
    public function testQuietToScript()
    {
        $expected = '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedQuietedScript.php' ) . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Quiet );
        $this->target .= 'quietedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testRouteToScript()
    
    public function testRouteToScript()
    {
        $expected = '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedRoutedScript.php' ) . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Route );
        $this->target .= 'routedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type_Script $script, & $expected )
    
    private function xRescript( ScriptReorganizer_Type_Script $script, & $expected )
    {
        $script->load( $this->source);
        $script->reformat();
        $script->save( $this->target);
        
        $this->assertTrue( $expected === file_get_contents( $this->target ) );
    }
    
    // }}}
    
    // {{{ private properties
    
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
