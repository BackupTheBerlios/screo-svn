<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Type :: Decorator
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
 * Extends <kbd>ScriptReorganizer_Type</kbd>
 */
require_once 'ScriptReorganizer/Type.php';

/**
 * Base class to be extended by (type) decorators to apply
 *
 * All decorators must follow the naming convention
 * <kbd>ScriptReorganizer_Type_Decorator_<Decorator></kbd>.
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
abstract class ScriptReorganizer_Type_Decorator extends ScriptReorganizer_Type
{
    // {{{ public function __construct( ScriptReorganizer_Type $type )
    
    /**
     * Constructor
     *
     * @param ScriptReorganizer_Type $type a <kbd>ScriptReorganizer_Type</kbd> to
     *        decorate
     */
    public function __construct( ScriptReorganizer_Type $type )
    {
        $this->type = $type;
    }
    
    // }}}
    // {{{ public function __destruct()
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset( $this->type );
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
        $this->type->load( $file );
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
        $this->type->reformat();
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
        $this->type->save( $file );
    }
    
    // }}}
    
    // {{{ package function _getContent()
    
    /**
     * Gets the script's content currently being reorganized
     *
     * @visibility package restricted
     * @return     string a string representing the script's content
     */
    public function _getContent()
    {
        return $this->type->_getContent();
    }
    
    // }}}
    // {{{ package function _setContent( $content )
    
    /**
     * Sets the script's content currently being reorganized
     *
     * @visibility package restricted
     * @param      string $content a string representing the content's replacement
     * @return     void
     */
    public function _setContent( $content )
    {
        $this->type->_setContent( $content );
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the type to decorate
     *
     * @var ScriptReorganizer_Type
     */
    private $type = null;
    
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
