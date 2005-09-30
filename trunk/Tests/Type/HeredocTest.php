<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Script.php';

class ScriptReorganizer_Tests_Type_HeredocTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $os = PHP_EOL == "\r\n" ? '-win' : ( PHP_EOL == "\n" ? '-unix' : '-mac' );
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp . 'heredoc' . $os . '.php';
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
    
    // {{{ public function testDefaultPackedHeredoc()
    
    public function testDefaultPackedHeredoc()
    {
        $expected = '<?php' . (include 'ScriptReorganizer/Tests/files/expectedDefaultPackedHeredoc.php' ) . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack );
        $this->target .= 'defaultPackedHeredoc.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testAdvancedPackedHeredoc()
    
    public function testAdvancedPackedHeredoc()
    {
        $expected = '<?php' . (include 'ScriptReorganizer/Tests/files/expectedAdvancedPackedHeredoc.php' ) . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) );
        $this->target .= 'advancedPackedHeredoc.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type_Script $script, & $expected )
    
    private function xRescript( ScriptReorganizer_Type_Script $script, & $expected )
    {
        $script->load( $this->source );
        $script->reformat();
        $script->save( $this->target );
        
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
