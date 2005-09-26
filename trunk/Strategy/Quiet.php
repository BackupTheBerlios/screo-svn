<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Strategy :: Quiet
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
 * @subpackage Strategy
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/ScriptReorganizer
 * @filesource
 */

/**
 * Implements <kbd>ScriptReorganizer_Strategy</kbd>
 */
require_once 'ScriptReorganizer/Strategy.php';

/**
 * Uses <kbd>ScriptReorganizer_Strategy_Route</kbd>
 */
require_once 'ScriptReorganizer/Strategy/Route.php';

/**
 * Standard strategy
 *
 * Reorganizes scripts by stripping off single and multiple line comments as well as
 * by applying the {@link ScriptReorganizer_Strategy_Route Route} strategy.
 *
 * @category   Tools
 * @package    ScriptReorganizer
 * @subpackage Strategy
 * @author     Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright  2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/ScriptReorganizer
 */
class ScriptReorganizer_Strategy_Quiet implements ScriptReorganizer_Strategy
{
    // {{{ public function __construct()
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->route = new ScriptReorganizer_Strategy_Route;
    }
    
    // }}}
    
    // {{{ public function reformat( & $content, $eol )
    
    /**
     * Performs the main reorganization of the script's content
     *
     * @param  string &$content a string representing the script's content
     * @param  string $eol a string representing the EOL identifier to use
     * @return string a string representing the reorganized content
     */
    public function reformat( & $content, $eol )
    {
        $identifiers = array(
            'multiLineComments' => '"[{};,' . $eol . ']([ \t]*/\*(.|[' . $eol . '])*?\*/)"',
            'singleLineComments' => '"[{};,' . $eol . ']([ \t]*//[^' . $eol . ']*)"'
        );
        
        foreach ( $identifiers as $identifier ) {
            if ( preg_match_all( $identifier, $content, $matches ) ) {
                foreach ( $matches[1] as $comment ) {
                    $content = str_replace( $comment, '', $content );
                }
            }
        }
        
        return $this->route->reformat( $content, $eol );
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the helper strategy
     *
     * @var ScriptReorganizer_Strategy_Route
     */
    private $route = null;
    
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
