<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer :: Factory
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
 * @since      File available since Release 0.4.0
 * @filesource
 */

/**
 * Throws <kbd>ScriptReorganizer_Factory_Exception</kbd>
 */
require_once 'ScriptReorganizer/Factory/Exception.php';

/**
 * Throws <kbd>ScriptReorganizer_Type_Decorator_Exception</kbd>
 */
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

/**
 * Factory/Facade for easy <kbd>ScriptReorganizer_Type</kbd> object creation
 *
 * @category  Tools
 * @package   ScriptReorganizer
 * @author    Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright 2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/ScriptReorganizer
 * @since     Class available since Release 0.4.0
 */
class ScriptReorganizer_Factory
{
    // {{{ public static function create( $type, $strategy [, $decorator ...] )
    
    /**
     * Creates on object according to the specifications supplied classes-wise
     *
     * The names of the classes declared - the last differentiating part - are
     * case-sensitive. E.g., <kbd>ScriptReorganizer_Type_Script</kbd> has to be
     * denoted as 'Script' - on Windows boxes the case does not matter.
     *
     * @param  mixed $type a string or an array representing the name of the
     *         <kbd>ScriptReorganizer_Type</kbd> to instantiate
     * @param  mixed $strategy a string representing the name or an array
     *         representing the name and the optional argument of the
     *         <kbd>ScriptReorganizer_Strategy</kbd> to instantiate
     * @param  mixed $decorator a variable-length argument list of strings
     *         representing the names and/or of arrays representing the names and the
     *         optional arguments of the <kbd>ScriptReorganizer_Type_Decorator</kbd>s
     *         to instantiate
     * @return ScriptReorganizer_Type the <kbd>ScriptReorganizer_Type</kbd> object
     *         created
     * @throws {@link ScriptReorganizer_Factory_Exception ScriptReorganizer_Factory_Exception}
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public static function create( $type, $strategy )
    {
        $arguments = func_get_args();
        $classes = & self::importAndCheck( $arguments );
        
        $argument = is_array( $strategy ) && isset( $strategy[1] ) ? $strategy[1] : null;
        $strategy = new $classes[1]( $argument );
        
        $type = new $classes[0]( $strategy );
        
        for ( $i = count( $arguments ) - 1; $i > 1; $i-- ) {
            $argument = is_array( $arguments[$i] ) && isset( $arguments[$i][1] ) ? $arguments[$i][1] : null;
            $type = new $classes[$i]( $type, $argument );
        }
        
        return $type;
    }
    
    // }}}
    
    // {{{ private static function & importAndCheck( & $arguments )
    
    /**
     * Checks that the required classes are available after having imported them
     *
     * @param  array &$arguments an array holding the arguments to process
     * @return array an array holding the classes' names that have been imported
     * @throws {@link ScriptReorganizer_Factory_Exception ScriptReorganizer_Factory_Exception}
     */
    private static function & importAndCheck( & $arguments )
    {
        $classes = array();
        
        foreach ( $arguments as $argument ) {
            if ( !is_string( $argument ) && !is_array( $argument ) || empty( $argument ) ) {
                throw new ScriptReorganizer_Factory_Exception(
                    'Argument(s) either not of type string/array or empty'
                );
            }
            
            if ( is_array( $argument ) ) {
                if ( !isset( $argument[0] ) || !is_string( $argument[0] ) || empty( $argument[0] ) ) {
                    throw new ScriptReorganizer_Factory_Exception(
                        'Array argument(s) either not of type string or empty'
                    );
                }
                
                $argument = $argument[0];
            }
            
            $classes[] = $argument;
        }
        
        $classes[0] = 'ScriptReorganizer_Type_' . $classes[0];
        $classes[1] = 'ScriptReorganizer_Strategy_' . $classes[1];
        
        for ( $i = 2, $j = count( $classes ); $i < $j; $i++ ) {
            $classes[$i] = 'ScriptReorganizer_Type_Decorator_' . $classes[$i];
        }
        
        $root = realpath( dirname( __FILE__ ) . '/..' ) . DIRECTORY_SEPARATOR;
        
        foreach ( $classes as $class ) {
            @include_once $root . str_replace( '_', DIRECTORY_SEPARATOR, $class ) . '.php';
            
            if ( !class_exists( $class ) ) {
                throw new ScriptReorganizer_Factory_Exception(
                    'Class ' . $class . ' not found'
                );
            }
        }
        
        return $classes;
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
