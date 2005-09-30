<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Strategy/Quiet.php';

require_once 'ScriptReorganizer/Type/Exception.php';
require_once 'ScriptReorganizer/Type/Library.php';
require_once 'ScriptReorganizer/Type/Script.php';

class ScriptReorganizer_Tests_Type_EolTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function setUp()
    
    public function setUp()
    {
        $rp = realpath( dirname( __FILE__ ) . '/../files' ) . DIRECTORY_SEPARATOR;
        
        $this->source = $rp;
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
    
    // {{{ public function testOptimizeMacintoshScript()
    
    public function testOptimizeMacintoshScript()
    {
        $expected = '<?php' . "\r\r"
            . "print 'Macintosh EOL style used.';\r\r"
            . "require_once 'ScriptReorganizer/Tests/files/eol-unix.php';\r"
            . "require_once 'ScriptReorganizer/Tests/files/eol-win.php';"
            . "\r\r" . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-mac.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOptimizeUnixScript()
    
    public function testOptimizeUnixScript()
    {
        $expected = '<?php' . "\n\n"
            . "print 'Unix EOL style used.';\n\n"
            . "require_once 'ScriptReorganizer/Tests/files/eol-win.php';\n"
            . "require_once 'ScriptReorganizer/Tests/files/eol-mac.php';"
            . "\n\n" . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-unix.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOptimizeWindowsScript()
    
    public function testOptimizeWindowsScript()
    {
        $expected = '<?php' . "\r\n\r\n"
            . "print 'Windows EOL style used.';\r\n\r\n"
            . "require_once 'ScriptReorganizer/Tests/files/eol-unix.php';\r\n"
            . "require_once 'ScriptReorganizer/Tests/files/eol-mac.php';"
            . "\r\n\r\n" . '?>';
        $script = new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-win.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOptimizeMacintoshLibrary()
    
    public function testOptimizeMacintoshLibrary()
    {
        $expected = '<?php' . "\r\r"
            . "print 'Macintosh EOL style used.';\r\r"
            . "print 'Unix EOL style used.';\r\r"
            . "print 'Windows EOL style used.';"
            . "\r\r" . '?>';
        $script = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-mac.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOptimizeUnixLibrary()
    
    public function testOptimizeUnixLibrary()
    {
        $expected = '<?php' . "\n\n"
            . "print 'Unix EOL style used.';\n\n"
            . "print 'Windows EOL style used.';\n\n"
            . "print 'Macintosh EOL style used.';"
            . "\n\n" . '?>';
        $script = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-unix.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    // {{{ public function testOptimizeWindowsLibrary()
    
    public function testOptimizeWindowsLibrary()
    {
        $expected = '<?php' . "\r\n\r\n"
            . "print 'Windows EOL style used.';\r\n\r\n"
            . "print 'Unix EOL style used.';\r\n\r\n"
            . "print 'Macintosh EOL style used.';"
            . "\r\n\r\n" . '?>';
        $script = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Quiet );
        
        $this->source .= 'eol-win.php';
        $this->target .= 'optimizedScript.php';
        
        $this->xRescript( $script, $expected );
    }
    
    // }}}
    
    // {{{ private function xRescript( ScriptReorganizer_Type_Script $script, & $expected )
    
    private function xRescript( ScriptReorganizer_Type $script, & $expected )
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
