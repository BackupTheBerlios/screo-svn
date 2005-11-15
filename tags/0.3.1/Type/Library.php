<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Type :: Library
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
 * @subpackage Type
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
 * Extends <kbd>ScriptReorganizer_Type</kbd>
 */
require_once 'ScriptReorganizer/Type.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Exception.php';

/**
 * Many-to-one reorganization
 *
 * Converts a script file and all included/required files to a single library file
 * according to the {@link ScriptReorganizer_Strategy Strategy} to apply.
 *
 * To avoid the processing of files' imports, which can change independently from the
 * code base at any time, transform the respective statement from a static to a
 * dynamic one, e.g. from <kbd>require_once 'configuration.php';</kbd> to
 * <kbd>require_once 'configuration' . '.php';</kbd>.
 *
 * If the advanced pack mode strategy is used for packaging, a non-ScriptReorganized
 * source code tree should be shipped together with the optimized one, to enable
 * third parties to track down undiscoverd bugs.
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @subpackage Type
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/ScriptReorganizer
 */
class ScriptReorganizer_Type_Library extends ScriptReorganizer_Type
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
        parent::__construct( $strategy );
        
        $this->imports = array();
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
        parent::load( $file );
        
        $baseDirectory = realpath( dirname( $file ) );
        $this->imports[] = $this->retrieveRealPath( $file, $baseDirectory );
        
        $this->_setContent( $this->resolveImports( $baseDirectory ) );
        
        $importException = '"< import of( file [^>]+)>"';
        
        if ( preg_match_all( $importException, $this->_getContent(), $matches ) ) {
            throw new ScriptReorganizer_Type_Exception(
                'Import of' . PHP_EOL . '-' . ( implode( PHP_EOL . '-', $matches[1] ) )
            );
        }
    }
    
    // }}}
    
    // {{{ private function resolveImports( $baseDirectory )
    
    /**
     * Adds all imported files' contents to the script currently being reorganized
     *
     * @param  string $baseDirectory a string representing the base path to use for
     *         resolving files' imports
     * @return string a string representing the old script's content with the import
     *         instructions replaced by the respective imported files' contents
     * @see    retrieveContent()
     */
    private function resolveImports( $baseDirectory )
    {
        $content = $this->_getContent();
        $resolvedContents = array();
        
        $eol = $this->getEolIdentifier( $content );
        
        $staticImport  = '"(;|[' . $eol . '])(([ \t]*)(include|require)(_once)?';
        $staticImport .= '[ \t]*\(?[ \t' . $eol . ']*[\'\"]([^\'\"]+)[\'\"]';
        $staticImport .= '[ \t' . $eol . ']*\)?[ \t]*;)"';
        
        // starting with PHPUnit2-2.3.0: Compilation failed: unmatched parentheses at offset 90?
        
        if ( @preg_match_all( $staticImport, $content, $matches ) ) {
            $i = 0;
            
            foreach ( $matches[6] as $file ) {
                $resolvedContents[] = $this->resolveImports(
                    $this->retrieveContent(
                        $file, '_once' === $matches[5][$i++], $baseDirectory
                    )
                );
            }
            
            $i = 0;
            
            foreach ( $matches[2] as $staticIdentifier ) {
                $indent = $matches[3][$i];
                
                $resolvedContent = str_replace(
                    $eol, $eol . $indent, $resolvedContents[$i++]
                );
                
                $staticIdentifier = '!' . str_replace(
                    '(', '\(', str_replace( ')', '\)', $staticIdentifier )
                ) . '!';
                
                $content = preg_replace(
                    $staticIdentifier, $resolvedContent, $content, 1
                );
            }
        }
        
        return $content;
    }
    
    // }}}
    // {{{ private function retrieveContent( $file, $importOnce, $baseDirectory )
    
    /**
     * Loads the file's content to be imported
     *
     * Avoids duplication of files being imported with <kbd>include_once</kbd> or
     * <kbd>require_once</kbd>.
     *
     * @param  string $file a string representing the file's name to be added
     * @param  boolean $importOnce boolean true, if the import instruction is either
     *         <kbd>include_once</kbd> or <kbd>require_once</kbd>; otherwise false
     * @param  string $baseDirectory a string representing the base path to use for
     *         resolving the files' imports
     * @return string a string representing the new base path to use for resolving
     *         future files' (relative) imports
     * @see    resolveImports(), retrieveRealPath()
     */
    private function retrieveContent( $file, $importOnce, $baseDirectory )
    {
        $realFile = $this->retrieveRealPath( $file, $baseDirectory );
        
        if ( $importOnce ) {
            if ( in_array( $realFile, $this->imports ) ) {
                $this->_setContent( '' );
                return 'will not be used';
            }
            
            $this->imports[] = $realFile;
        }
        
        try {
            parent::load( $realFile );
        } catch ( scriptReorganizer_Type_Exception $e ) {
            $file = $baseDirectory . DIRECTORY_SEPARATOR . $file;
            
            $this->_setContent(
                '< import of file ' . $file . ' failed >'
            );
        }
        
        return dirname( $realFile );
    }
    
    // }}}
    // {{{ private function retrieveRealPath( $file, $baseDirectory )
    
    /**
     * Delivers the file's realpath to be imported
     *
     * @param  string $file a string representing the file's name to be converted
     *         into a realpath identifier
     * @param  string $baseDirectory a string representing the base path to add to
     *         the current include_path directive, if needed
     * @return string a string representing the file's real path to be imported
     * @see    retrieveContent()
     */
    private function retrieveRealPath( $file, $baseDirectory )
    {
        if ( !is_file( $file ) ) {
            $includePaths = $baseDirectory . PATH_SEPARATOR . get_include_path();
            
            foreach ( explode( PATH_SEPARATOR, $includePaths ) as $includePath ) {
                $script = $includePath . DIRECTORY_SEPARATOR . $file;
                
                if ( is_file( $script ) ) {
                    $file = $script;
                    break;
                }
            }
        }
        
        return realpath( $file );
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the list of already imported file names
     *
     * @var array
     */
    private $imports = null;
    
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
