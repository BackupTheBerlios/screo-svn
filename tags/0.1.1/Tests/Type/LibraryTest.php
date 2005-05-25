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
require_once 'ScriptReorganizer/Type/Library.php';

class ScriptReorganizer_Tests_Type_LibraryTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'tree/root.php';
        $this->target = $rp;
    }
    
    // }}}
    // {{{ public function tearDown()
    
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
    
    // {{{ public function testFailingImport()
    
    public function testFailingImport()
    {
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack );
        $this->source .= '-FailingImport.php';
        
        try {
            $library->load( $this->source );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Type_Exception $e ) {
            $this->assertContains( ' failed ', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testPreventCyclicImports()
    
    public function testPreventCyclicImports()
    {
        $expected = '<?php' . PHP_EOL . PHP_EOL . "define( 'A', true ); define( 'B', true );" . PHP_EOL . PHP_EOL . '?>';
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack );
        $this->source = $this->target . 'ARequiresB.php';
        $this->target .= 'cyclicImports.php';
        
        $this->xRescript( $library, $expected );
    }
    
    // }}}
    
    // {{{ public function testPackToLibrary()
    
    public function testPackToLibrary()
    {
        $expected = '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedPackedLibrary.php' ) . '?>';
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'packedLibrary.php';
        
        $this->xRescript( $library, $expected );
    }
    
    // }}}
    // {{{ public function testQuietToLibrary()
    
    public function testQuietToLibrary()
    {
        $expected = '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedQuietedLibrary.php' ) . '?>';
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Quiet );
        $this->target .= 'quietedLibrary.php';
        
        $this->xRescript( $library, $expected );
    }
    
    // }}}
    // {{{ public function testRouteToLibrary()
    
    public function testRouteToLibrary()
    {
        $expected = '<?php' . ( include 'ScriptReorganizer/Tests/files/expectedRoutedLibrary.php' ) . '?>';
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Route );
        $this->target .= 'routedLibrary.php';
        
        $this->xRescript( $library, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type_Library $library, & $expected )
    
    private function xRescript( ScriptReorganizer_Type_Library $library, & $expected )
    {
        $library->load( $this->source );
        $library->reformat();
        $library->save( $this->target );
        
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
