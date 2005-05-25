<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer Strategy :: Pack
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
 * Uses <kbd>ScriptReorganizer_Strategy_Quiet</kbd>
 */
require_once 'ScriptReorganizer/Strategy/Quiet.php';

/**
 * Advanced strategy
 *
 * Reorganizes scripts by applying the {@link ScriptReorganizer_Strategy_Quiet Quiet}
 * strategy as well as by replacing (1) EOLs (2) two or more consecutive spaces
 * and/or tabs with a single space char.
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
class ScriptReorganizer_Strategy_Pack implements ScriptReorganizer_Strategy
{
    // {{{ public function reformat( & $content )
    
    /**
     * Performs the main reorganization of the script's content
     *
     * @param  string &$content a string representing the script's content
     * @return string a string representing the reorganized content
     */
    public function reformat( & $content )
    {
        $this->quiet or $this->quiet = new ScriptReorganizer_Strategy_Quiet;
        
        $multiSpacesAndOrTabs = '"[ \t]+"';
        
        $result = $this->quiet->reformat( $content );
        $result = str_replace( PHP_EOL, ' ', $result );
        $result = preg_replace( $multiSpacesAndOrTabs, ' ', $result );
        
        return trim( $result );
    }
    
    // }}}
    
    // {{{ private properties
    
    /**
     * Holds the helper strategy
     *
     * @var ScriptReorganizer_Strategy_Quiet
     */
    private $quiet = null;
    
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
