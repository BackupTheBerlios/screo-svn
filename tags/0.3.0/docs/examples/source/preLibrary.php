<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File: docs/examples/source/preLibrary.php
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

require_once 'ScriptReorganizer/Exception.php';
require_once 'ScriptReorganizer/Strategy/Exception.php';
require_once 'ScriptReorganizer/Type/Exception.php';
require_once 'ScriptReorganizer/Type/Decorator/Exception.php';

require_once 'ScriptReorganizer/Strategy.php';
require_once 'ScriptReorganizer/Type.php';

require_once 'ScriptReorganizer/Strategy/Route.php';
require_once 'ScriptReorganizer/Strategy/Quiet.php';
require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Decorator.php';
require_once 'ScriptReorganizer/Type/Library.php';
require_once 'ScriptReorganizer/Type/Script.php';

require_once 'ScriptReorganizer/Type/Decorator/AddFooter.php';
require_once 'ScriptReorganizer/Type/Decorator/AddHeader.php';
require_once 'ScriptReorganizer/Type/Decorator/Bcompile.php';
require_once 'ScriptReorganizer/Type/Decorator/Pharize.php';

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
