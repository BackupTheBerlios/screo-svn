<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File: docs/examples/compilePackedLibrary.php
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

/*
 * Example: self packaging of ScriptReorganizer into a compiled packed library.
 *
 * *Self* compilation is not possible due to class redeclaration error during the
 * compile run of the BCompiler extension. Only in this specific case a manual
 * process - i.e. without the ScriptReorganizer package - is needed.
 *
 * Be reminded that the pre-compiling of PHP code does increase the file size - however
 * the speed gain is considerable! The ScriptReorganizer_Type_Decorator_Bcompile class
 * has been added as a showcase. Use it at your pleasure.
 *
 * See the exemplary code below.
 */

if ( class_exists( 'ScriptReorganizer_Exception' ) ) {
    die( PHP_EOL . 'Run compilePackedLibrary.php separately' . PHP_EOL );
}

// initialize variables

$path = realpath( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;

$source = $path . 'target/Screo-packedLibrary.php';
$target = $path . 'target/Screo-compiledPackedLibrary.php';

if ( !file_exists( $source ) ) {
    die( PHP_EOL . 'Run packLibrary.php prior to the compile run' . PHP_EOL );
}

// do the job ... ;)

/*
 * The standard steps to follow for the compilation of (third party) libraries/scripts
 * are as follows:
 *
 * - import of the required ScriptReorganizer classes: Library or Script together
 *   with the strategy of your choice, even though the strategy does not influence
 *   the result as such - it is just needed for ScriptReorganizer to run correctly
 * - create the respective object
 * - load the file to compile and save the result - note again that the reformat
 *   method needs not to be invoked, but can be ;)
 *
 * Exemplary code:
 *
 * <?php
 *
 * require_once 'ScriptReorganizer/Exception.php';
 * require_once 'ScriptReorganizer/Strategy/Pack.php';
 *
 * require_once 'ScriptReorganizer/Type/Library.php';
 * require_once 'ScriptReorganizer/Type/Decorator/Bcompile.php';
 *
 * $library = new ScriptReorganizer_Type_Decorator_Bcompile(
 *     new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack )
 * );
 *
 * try {
 *     $library->load( <the script to be compiled> );
 *     $library->reformat(); // can be omitted
 *     $library->save( <the target as the result of the compile run> );
 * } catch ( ScriptReorganizer_Exception $e ) {
 *     // do some recovery here ...
 * }
 *
 * ?>
 *
 * That's it!
 *
 * ScriptReorganizer's added value: a library is packaged - i.e. a many-to-one file
 * optimization is performed - and compiled with the BCompiler extension for
 * achieving even more speed gain.
 */

if ( extension_loaded( 'bcompiler' ) ) {
    if ( $t = @fopen( $target, 'wb' ) ) {
        bcompiler_write_header( $t );
        bcompiler_write_file( $t, $source );
        bcompiler_write_footer( $t );
        
        @fclose( $t );
        
        echo PHP_EOL . 'target/Screo-compiledPackedLibrary.php created' . PHP_EOL;
    } else {
        die( PHP_EOL . $target . ' can not be opened for writing' . PHP_EOL );
    }
} else {
    die( PHP_EOL . 'BCompiler extension not loaded' . PHP_EOL );
}

// validate the compiled library

require_once $path . 'target/Screo-compiledPackedLibrary.php';

echo 'target/Screo-compiledPackedLibrary.php validation ';

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
