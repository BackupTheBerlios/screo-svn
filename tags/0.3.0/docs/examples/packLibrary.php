<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File: docs/examples/packLibrary.php
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
 * Example: self packaging of ScriptReorganizer into a packed library.
 *
 * A workaround for the *self* packaging is needed for the following reasons:
 *
 * - the identifier for a file import failure in the source of ScriptReorganizer
 *   triggers the expected exception, hence the optimizing job must be continued
 *   in the catch block.
 * - due to the EOL style autodetection's way ScriptReorganizer is using, the
 *   temporary library content assembled in the first run must be saved in a
 *   temporary file and loaded again for further processing.
 *
 * See the try-catch block below.
 */

// import classes

require_once 'ScriptReorganizer/Exception.php';
require_once 'ScriptReorganizer/Strategy/Pack.php';

require_once 'ScriptReorganizer/Type/Library.php';
require_once 'ScriptReorganizer/Type/Script.php';
require_once 'ScriptReorganizer/Type/Decorator/AddHeader.php';

// initialize variables

$header = <<< HEADER
/*
 * http://pear.php.net/package/ScriptReorganizer 0.3.0 - Packed Library
 *
 * PHP version 5
 *
 * Copyright (C) 2005 Stefano F. Rausch <stefano@rausch-e.net>
 *
 * LICENSE: This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation; either version 2.1 of the License, or (at your option) any
 * later version.
 */


HEADER;

$library = new ScriptReorganizer_Type_Library( new ScriptReorganizer_Strategy_Pack );

/*
 * Normally, this would be the standard way of instantiating a library type object,
 * just swap ScriptReorganizer_Type_Script with ScriptReorganizer_Type_Library.
 */

$script = new ScriptReorganizer_Type_Decorator_AddHeader(
    new ScriptReorganizer_Type_Script( new ScriptReorganizer_Strategy_Pack( true ) )
);

$path = realpath( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;

// do the job ... ;)

try {
    $library->load( $path . 'source/preLibrary.php' );
} catch ( ScriptReorganizer_Exception $e ) {
    $tempFile = $path . 'source/tempLibrary.php';
    
    $library->save( $tempFile );
    
    /*
     * A script type object has been used, for a many-to-one file optimization has
     * already taken place, albeit a library type object would have done the job too.
     */
    
    $script->load( $tempFile );
    $script->reformat( $header );
    $script->save( $path . 'target/Screo-packedLibrary.php' );
    
    unlink( $tempFile );
}

echo PHP_EOL . 'target/Screo-packedLibrary.php created' . PHP_EOL
    . 'Run packLibraryCheck.php separately to validate the result' . PHP_EOL;

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
