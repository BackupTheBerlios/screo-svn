<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer :: Type
 *
 * PHP version 5
 *
 * LICENSE: This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation; either version 2.1 of the License, or (at your option) any
 * later version.
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/ScriptReorganizer
 * @filesource
 */

/**
 * Depends on <kbd>ScriptReorganizer_Strategy</kbd>
 */
require_once 'ScriptReorganizer/Strategy.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Exception.php';

/**
 * Base class to be extended by (reorganizer) types to use
 *
 * All types must follow the naming convention
 * <kbd>ScriptReorganizer_Type_<Type></kbd>.
 *
 * @category  Tools
 * @package   ScriptReorganizer
 * @author    Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright 2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/ScriptReorganizer
 */
abstract class ScriptReorganizer_Type
{
    // {{{ public function __construct( ScriptReorganizer_Strategy $strategy )
    
    /**
     * Constructor
     *
     * @param ScriptReorganizer_Strategy $strategy a
     *        <kbd>ScriptReorganizer_Strategy</kbd> to apply
     */
    public function __construct( ScriptReorganizer_Strategy $strategy )
    {
        $this->strategy = $strategy;
        
        $this->heredocIndent = '"[' . PHP_EOL . ']([ \t]+)[^' . PHP_EOL . ']+;$"';
        
        $heredocs  = '"[<]{3}[ \t]*([^' . PHP_EOL . '])+[' . PHP_EOL . ']';
        $heredocs .= '(.|[' . PHP_EOL . '])+?\1;"';
        
        $this->heredocsIdentifier = $heredocs;
    }
    
    // }}}
    // {{{ public function __destruct()
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset( $this->strategy );
    }
    
    // }}}
    
    // {{{ public function getContent()
    
    /**
     * Gets the script's content currently being reorganized
     *
     * @return string a string representing the script's content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    // }}}
    // {{{ public function load( $file )
    
    /**
     * Loads the script's content to be reorganized from disk
     *
     * @param  string $file a string representing the file's name to load
     * @return void
     * @throws {@link ScriptReorganizer_Type_Exception ScriptReorganizer_Type_Exception}
     */
    public function load( $file )
    {
        $content = @file_get_contents( $file );
        
        if ( false === $content ) {
            throw new ScriptReorganizer_Type_Exception(
                'File ' . $file . ' is not readable.'
            );
        }
        
        $result = trim( $content );
        $result = preg_replace( '"^<\?php"', '', $result );
        $result = preg_replace( '"\?>$"', '', $result );
        
        $this->setContent( $result );
    }
    
    // }}}
    // {{{ public function reformat()
    
    /**
     * Reorganizes the script's content by applying the chosen
     * {@link ScriptReorganizer_Strategy Strategy}
     *
     * @return void
     */
    public function reformat()
    {
        $content = $this->getContent();
        
        $this->maskHeredocs( $content );
        $content = $this->strategy->reformat( $content );
        $this->unmaskHeredocs( $content );
        
        $this->setContent( $content );
    }
    
    // }}}
    // {{{ public function save( $file )
    
    /**
     * Saves the reorganized script's content to disk
     *
     * @param  string $file a string representing the file's name to save
     * @return void
     * @throws {@link ScriptReorganizer_Type_Exception ScriptReorganizer_Type_Exception}
     */
    public function save( $file )
    {
        $content = '<?php' . PHP_EOL . PHP_EOL . $this->getContent() . PHP_EOL
            . PHP_EOL . '?>';
        
        if ( false === @file_put_contents( $file, $content ) ) {
            throw new ScriptReorganizer_Type_Exception(
                'File ' . $file . ' is not writable.'
            );
        }
    }
    
    // }}}
    // {{{ public function setContent( $content )
    
    /**
     * Sets the script's content currently being reorganized
     *
     * @param  string $content a string representing the content's replacement
     * @return void
     */
    public function setContent( $content )
    {
        $this->content = $content;
    }
    
    // }}}
    
    // {{{ private function maskHeredocs( & $content )
    
    /**
     * Hides Heredoc strings before the reorganization process
     *
     * @param  string &$content a string representing the script's content
     * @return void
     * @see    unmaskHeredocs(), reformat()
     * @since  Method available since Release 0.2.1
     */
    private function maskHeredocs( & $content )
    {
        if ( preg_match_all( $this->heredocsIdentifier, $content, $this->heredocs ) ) {
            $i = 0;
            
            foreach ( $this->heredocs[0] as $heredoc ) {
                $content = str_replace(
                    $heredoc, '< Heredoc ' . $i++ . ' >', $content
                );
                
                if ( preg_match( $this->heredocIndent, $heredoc, $indent ) ) {
                    $this->heredocs[0][$i-1] = str_replace(
                        PHP_EOL . $indent[1], PHP_EOL, $heredoc
                    );
                }
            }
        }
    }
    
    // }}}
    // {{{ private function unmaskHeredocs( & $content )
    
    /**
     * Unhides Heredoc strings after the reorganization process
     *
     * @param  string &$content a string representing the script's content
     * @return void
     * @see    maskHeredocs(), reformat()
     * @since  Method available since Release 0.2.1
     */
    private function unmaskHeredocs( & $content )
    {
        $i = 0;
        
        foreach ( $this->heredocs[0] as $heredoc ) {
            $hd = '< Heredoc ' . $i++ . ' >';
            $trailingSpace = false !== strpos( $content, $hd . ' ' );
            
            $content = str_replace(
                $hd . ( $trailingSpace ? ' ' : '' ),
                $heredoc . ( $trailingSpace ? PHP_EOL : '' ), $content
            );
        }
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the script's content currently being reorganized
     *
     * @var string
     */
    private $content = '';
    
    /**
     * Holds the regular expression for the indent level to remove from Heredoc
     * strings
     *
     * @var string
     */
    private $heredocIndent = '';
    
    /**
     * Holds the list of Heredoc strings to un-/mask
     *
     * @var array
     */
    private $heredocs = null;
    
    /**
     * Holds the regular expression for Heredoc strings
     *
     * @var string
     */
    private $heredocsIdentifier = '';
    
    /**
     * Holds the strategy to apply
     *
     * @var ScriptReorganizer_Strategy
     */
    private $strategy = null;
    
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
