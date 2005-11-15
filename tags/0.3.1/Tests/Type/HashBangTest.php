<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Library.php';
require_once 'ScriptReorganizer/Type/Script.php';

class ScriptReorganizer_Tests_Type_HashBangTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $os = PHP_EOL == "\r\n" ? '-win' : ( PHP_EOL == "\n" ? '-unix' : '-mac' );
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'hashBang' . $os . '.php';
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
    
    // {{{ public function testDefaultPackedScriptHashBang()
    
    public function testDefaultPackedScriptHashBang()
    {
        $expected = include 'ScriptReorganizer/Tests/files/expectedDefaultPackedScriptHashBang.php';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'defaultPackedScriptHashBang.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testDefaultPackedLibraryHashBang()
    
    public function testDefaultPackedLibraryHashBang()
    {
        $expected = include 'ScriptReorganizer/Tests/files/expectedDefaultPackedLibraryHashBang.php';
        $library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'defaultPackedLibraryHashBang.php';
        
        $this->xRescript( $library, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type $type, & $expected )
    
    private function xRescript( ScriptReorganizer_Type $type, & $expected )
    {
        $type->load( $this->source );
        $type->reformat();
        $type->save( $this->target );
        
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
