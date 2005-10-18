<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Type Decorator :: Bcompile
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
 * @since      File available since Release 0.3.0
 * @filesource
 */

/**
 * Depends on <kbd>ScriptReorganizer_Type</kbd>
 */
require_once 'ScriptReorganizer/Type.php';

/**
 * Extends <kbd>ScriptReorganizer_Type_Decorator</kbd>
 */
require_once 'ScriptReorganizer/Type/Decorator.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Decorator_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

/**
 * Decorator/Adapter for encoding a PHP source file in bytecodes
 *
 * If a script or a library is bcompiled, a non-ScriptReorganized source code tree
 * should be shipped together with the optimized one, to enable third parties to
 * track down undiscoverd bugs.
 *
 * ANN: Decoration of a directly sequencing Bcompile-Decorator or Pharize-Decorator
 * is not allowed.
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @subpackage Type_Decorator
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/ScriptReorganizer
 * @since      Class available since Release 0.3.0
 */
class ScriptReorganizer_Type_Decorator_Bcompile extends ScriptReorganizer_Type_Decorator
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
        $constraint = '';
        
        if ( $type instanceof ScriptReorganizer_Type_Decorator_Bcompile ) {
            $constraint = 'Bcompile-Decorator';
        } else if ( class_exists( 'ScriptReorganizer_Type_Decorator_Pharize', false ) ) {
            if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) {
                $constraint = 'Pharize-Decorator';
            }
        }
        
        if ( $constraint ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'Decoration of a directly sequencing ' . $constraint . ' not allowed'
            );
        }
        
        if ( !extension_loaded( 'bcompiler' ) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'PHP Extension bcompiler not loaded'
            );
        }
        
        parent::__construct( $type );
    }
    
    // }}}
    
    // {{{ public function save( $file )
    
    /**
     * Saves the reorganized script's content as encoded bytecode to disk
     *
     * @param  string $file a string representing the file's name to save
     * @return void
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function save( $file )
    {
        $source = 'source.' . md5($file);
        
        @file_put_contents( $source, '<?php ' . $this->getContent() . ' ?>' );
        
        if ( is_file( $source ) ) {
            if ( $target = @fopen( $file, 'wb' ) ) {
                bcompiler_write_header( $target );
                bcompiler_write_file( $target, $source );
                bcompiler_write_footer( $target );
                
                @fclose( $target );
            }
            
            unlink( $source );
        }
        
        if ( !is_file( $file ) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception(
                'BCompiler file ' . $file . ' is not writable'
            );
        }
    }
    
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
