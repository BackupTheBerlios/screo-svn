<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Type Decorator :: AddHeader
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
 * Decorator for adding a header to the script to reorganize
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @subpackage Type_Decorator
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/ScriptReorganizer
 */
class ScriptReorganizer_Type_Decorator_AddHeader extends ScriptReorganizer_Type_Decorator
{
    // {{{ public function __construct( ScriptReorganizer_Type $type, $header = '' )
    
    /**
     * Constructor
     *
     * @param ScriptReorganizer_Type $type a <kbd>ScriptReorganizer_Type</kbd> to
     *        decorate
     * @param string $header a string representing the (optional) default header to
     *        prepend
     */
    public function __construct( ScriptReorganizer_Type $type, $header = '' )
    {
        parent::__construct( $type );
        
        $this->header = $header;
    }
    
    // }}}
    
    // {{{ public function reformat( $header = null )
    
    /**
     * Reorganizes the script's content by applying the chosen
     * {@link ScriptReorganizer_Strategy Strategy}
     *
     * @param  string $header a string representing the (optional) overriding header
     *         to prepend
     * @return void
     * @throws {@link ScriptReorganizer_Type_Decorator_Exception ScriptReorganizer_Type_Decorator_Exception}
     */
    public function reformat( $header = null )
    {
        if ( null !== $header ) {
            $this->header = $header;
        }
        
        if ( !is_string( $this->header ) ) {
            throw new ScriptReorganizer_Type_Decorator_Exception (
                'Argument $header for AddHeader-Decorator not of type string'
            );
        }
        
        parent::reformat();
        $this->setContent( $this->header . $this->getContent() );
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the header to prepend
     *
     * @var string
     */
    private $header = null;
    
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
