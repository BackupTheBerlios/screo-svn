<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Type Decorator :: Pharize
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
 * @subpackage Type_Decorator
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/ScriptReorganizer
 * @filesource
 */

/**
 * Uses <kbd>PHP_Archive_Creator</kbd>
 */
require_once 'PHP/Archive/Creator.php';

/**
 * Depends on <kbd>ScriptReorganizer_Type</kbd>
 */
require_once 'ScriptReorganizer/Type.php';

/**
 * Extends <kbd>ScriptReorganizer_Type_Decorator</kbd>
 */
require_once 'ScriptReorganizer/Type/Decorator.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Exception.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Decorator_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

/**
 * Decorator/Adapter for creating a <kbd>PHP_Archive</kbd>
 *
 * Constraints: <kbd>Pharize</kbd> must be the first in a list of chained decorators
 * to apply, for it only supports the sequencing decorators' methods <kbd>load</kbd>
 * and <kbd>reformat</kbd>. The correct (general) form of use is:
 * <pre>
 * &lt;?php
 *
 * $type = new ScriptReorganizer_Type_Decorator_Pharize(
 *     [new &lt;Decorators&gt;(] new &lt;Type&gt;( new &lt;Strategy&gt; ) [)]
 * );
 *
 * ?&gt;
 * </pre>
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @subpackage Type_Decorator
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/ScriptReorganizer
 * @todo       implement method <kbd>loadDirectory</kbd>
 */
class ScriptReorganizer_Type_Decorator_Pharize extends ScriptReorganizer_Type_Decorator
{
    // {{{ public function __construct( ScriptReorganizer_Type $type )
    
    /**
     * Constructor
     *
     * @param  ScriptReorganizer_Type $type a <kbd>ScriptReorganizer_Type</kbd> to
     *         decorate
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function __construct( ScriptReorganizer_Type $type )
    {
        if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'Decoration of directly sequencing Pharize-Decorators not allowed'
            );
        }
        
        parent::__construct( $type );
        
        $this->files = array();
        $this->magic = array();
    }
    
    // }}}
    
    // {{{ public function load( $source, $target, $magicRequire = false )
    
    /**
     * Loads the script's content to be reorganized from disk
     *
     * @param  string $source a string representing the file's name to load
     * @param  string $target a string representing the file's location in the PHP
     *         Archive
     * @param  boolean $magicRequire boolean true, for phar-stream activation within
     *         the PHP Arcive; otherwise false
     * @return void
     * @throws {@link ScriptReorganizer_Type_Exception ScriptReorganizer_Type_Exception}
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function load( $source, $target, $magicRequire = false )
    {
        parent::load( $source );
        
        if ( !is_string( $target ) || '' == $target ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'Argument $target for Pharize-Decorator not of type string'
            );
        }
        
        $content = $this->getContent();
        
        $this->loadContent( $content, $target, $magicRequire );
    }
    
    // }}}
    // {{{ public function loadDirectory ( ... )
    
    /*
     * to be implemented
     */
    
    // }}}
    // {{{ public function loadFiles ( $files, $magicRequire = false )
    
    /**
     * Loads the scripts' content to be reorganized from disk
     *
     * @param array $files an associative array holding all files' name to load and
     *        the corresponding files' locations in the PHP Archive 
     * @param  boolean $magicRequire boolean true, for phar-stream activation within
     *         the PHP Arcive; otherwise false
     * @return void
     * @throws {@link ScriptReorganizer_Type_Exception ScriptReorganizer_Type_Exception}
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function loadFiles( $files, $magicRequire = false )
    {
        if ( !is_array( $files ) || empty( $files) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'Argument $files for Pharize-Decorator not of type array'
            );
        }
        
        foreach ( $files as $source => $target ) {
            $this->load( $source, $target, $magicRequire );
        }
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
        foreach ( $this->files as $target => $content ) {
            $this->setContent( $content );
            parent::reformat();
            $this->files[$target] = $this->getContent();
        }
    }
    
    // }}}
    // {{{ public function save( $file, $initFile = 'index.php', $compress = false, $allowDirectAccess = false )
    
    /**
     * Saves the PHP Archive to disk
     *
     * @param  string $file a string representing the PHP Archive file's name to save
     * @param  string $initFile a string representing the file's name called by
     *         default upon PHAR execution
     * @param  boolean $compress boolean true, if the files have to be compressed;
     *         otherwise false
     * @param  mixed $allowDirectAccess boolean true, for unrestricted file access;
     *         boolean false, for access restricted to the init file; a string
     *         representing the restricted file type (extension) access
     * @return void
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function save( $file, $initFile = 'index.php', $compress = false, $allowDirectAccess = false )
    {
        $archive = new PHP_Archive_Creator(
            $initFile, $compress, $allowDirectAccess
        );
        
        $additionErrors = array();
        
        foreach ( $this->files as $target => $content ) {
            $content = '<?php ' . $content . ' ?>';
            
            if ( !$archive->addString( $content, $target, $this->magic[$target] ) ) {
                $additionErrors[] = $target;
            }
        }
        
        if ( !empty( $additionErrors ) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'Could not add ' . PHP_EOL . '-'
                . implode( PHP_EOL . '- file ', $additionErrors )
                . PHP_EOL . 'to PHP Archive file ' . $file
            );
        }
        
        $archive->savePhar( $file );
        
        if ( !is_file( $file ) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'PHP Archive file ' . $file . ' is not writable'
            );
        }
    }
    
    // }}}
    
    // {{{ private function loadContent( $content, $target, $magicRequire = false )
    
    /**
     * Loads a string as the file's content to add to the PHP Archive
     *
     * @param  string $content a string representing the file's content to add
     * @param  string $target a string representing the file's location in the PHP
     *         Archive
     * @param  boolean $magicRequire boolean true, for phar-stream activation within
     *         the PHP Arcive; otherwise false
     * @return void
     */
    private function loadContent( $content, $target, $magicRequire = false )
    {
        $this->files[$target] = $content;
        $this->magic[$target] = $magicRequire;
    }
    
    // }}}
    
    // {{{ private properties
    
    /*
     * Holds the files to add to the PHP Archive
     *
     * @var array
     */
    private $files = null;
    
    /*
     * Holds the <kbd><import>_once</kbd> replacement directives for the files to add
     *
     * @var array
     */
    private $magic = null;
    
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
