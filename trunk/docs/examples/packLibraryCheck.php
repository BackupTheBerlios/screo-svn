<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File: docs/examples/packLibraryCheck.php
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
 * @since      File available since Release 0.3.0
 */

if ( class_exists( 'ScriptReorganizer_Exception' ) ) {
    die( 'Run packLibraryCheck.php separately' );
}

$library = realpath( dirname( __FILE__ ) ) . '/target/Screo-packedLibrary.php';

if ( !file_exists( $library ) ) {
    die( PHP_EOL . 'Run packLibrary.php prior to the validation of target/Screo-packedLibrary.php' . PHP_EOL );
}

require_once $library;

echo PHP_EOL . 'target/Screo-packedLibrary.php validation ';

if ( class_exists( 'ScriptReorganizer_Exception' ) ) {
    echo 'successful';
} else {
    echo 'not successful';
}

echo PHP_EOL;

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
