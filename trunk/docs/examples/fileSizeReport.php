<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File: docs/examples/fileSizeReport.php
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
 * @since      File available sind Release 0.3.0
 */

clearstatcache();

$path = realpath( dirname( __FILE__ ) . '/../..' ) . DIRECTORY_SEPARATOR;

if ( !file_exists( $path . '/docs/examples/target/Screo-packedLibrary.php' ) ) {
    die( 'Run packLibrary.php prior to the file size report' );
}

if ( !file_exists( $path . '/docs/examples/target/Screo-compiledPackedLibrary.php' ) ) {
    die( 'Run compilePackedLibrary.php prior to the file size report' );
}

echo PHP_EOL . 'File size report:' . PHP_EOL;

// calculate the bytes used for all the relevant (well-documented) 16 classes

$originalSize  = filesize( $path . 'Exception.php' );
$originalSize += filesize( $path . 'Strategy.php' );
$originalSize += filesize( $path . 'Type.php' );

$originalSize += filesize( $path . 'Strategy/Exception.php' );
$originalSize += filesize( $path . 'Strategy/Route.php' );
$originalSize += filesize( $path . 'Strategy/Quiet.php' );
$originalSize += filesize( $path . 'Strategy/Pack.php' );

$originalSize += filesize( $path . 'Type/Exception.php' );
$originalSize += filesize( $path . 'Type/Decorator.php' );
$originalSize += filesize( $path . 'Type/Library.php' );
$originalSize += filesize( $path . 'Type/Script.php' );

$originalSize += filesize( $path . 'Type/Decorator/Exception.php' );
$originalSize += filesize( $path . 'Type/Decorator/AddFooter.php' );
$originalSize += filesize( $path . 'Type/Decorator/AddHeader.php' );
$originalSize += filesize( $path . 'Type/Decorator/Bcompile.php' );
$originalSize += filesize( $path . 'Type/Decorator/Pharize.php' );

echo PHP_EOL . '- bytes used for the original classes:  ' . $originalSize
    . ' =  ' . round( $originalSize / 1024, 2 ) . ' KB';

// get the bytes used for the packed library

$packedLibrary = filesize( $path . 'docs/examples/target/Screo-packedLibrary.php' );

echo PHP_EOL . '- bytes used for the packed library  :  ' . $packedLibrary
    . ' =  ' . round( $packedLibrary / 1024, 2 ) . ' KB'
    . ' (' . ( round( $packedLibrary / $originalSize * 100, 2 ) ) . '%)';

// get the bytes used for the compiled library

$compiledLibrary = filesize( $path . 'docs/examples/target/Screo-compiledPackedLibrary.php' );

echo PHP_EOL . '- bytes used for the compiled library: ' . $compiledLibrary
    . ' = ' . round( $compiledLibrary / 1024, 2 ) . ' KB'
    . ' (' . ( round( $compiledLibrary / $originalSize * 100, 2 ) ) . '%)';

echo PHP_EOL;

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
