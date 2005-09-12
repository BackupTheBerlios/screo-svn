<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ScriptReorganizer :: Strategy
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
 * Interface to be implemented by (reorganizer) strategies to apply
 *
 * All strategies must follow the naming convention
 * <kbd>ScriptReorganizer_Strategy_<Strategy></kbd>.
 *
 * @category  Tools
 * @package   ScriptReorganizer
 * @author    Stefano F. Rausch <stefano@rausch-e.net>
 * @copyright 2005 Stefano F. Rausch <stefano@rausch-e.net>
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/ScriptReorganizer
 */
interface ScriptReorganizer_Strategy
{
    // {{{ public function reformat( & $content )
    
    /**
     * Performs the main reorganization of the script's content
     *
     * @param  string &$content a string representing the script's content
     * @return string a string representing the reorganized content
     */
    public function reformat( & $content );
    
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
