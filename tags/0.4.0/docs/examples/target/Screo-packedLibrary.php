<?php

/*
 * http://pear.php.net/package/ScriptReorganizer 0.4.0 alpha - Packed Library
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

require_once 'PEAR/' . 'Exception.php'; class ScriptReorganizer_Exception extends PEAR_Exception { } class ScriptReorganizer_Factory_Exception extends ScriptReorganizer_Exception { } class ScriptReorganizer_Strategy_Exception extends ScriptReorganizer_Exception { } class ScriptReorganizer_Type_Exception extends ScriptReorganizer_Exception { } class ScriptReorganizer_Type_Decorator_Exception extends ScriptReorganizer_Type_Exception { } class ScriptReorganizer_Factory { public static function create( $type, $strategy ) { $arguments = func_get_args(); $classes = & self::importAndCheck( $arguments ); $argument = is_array( $strategy ) && isset( $strategy[1] ) ? $strategy[1] : null; $strategy = new $classes[1]( $argument ); $type = new $classes[0]( $strategy ); for ( $i = count( $arguments ) - 1; $i > 1; $i-- ) { $argument = is_array( $arguments[$i] ) && isset( $arguments[$i][1] ) ? $arguments[$i][1] : null; $type = new $classes[$i]( $type, $argument ); } return $type; } private static function & importAndCheck( & $arguments ) { $classes = array(); foreach ( $arguments as $argument ) { if ( !is_string( $argument ) && !is_array( $argument ) || empty( $argument ) ) { throw new ScriptReorganizer_Factory_Exception( 'Argument(s) either not of type string/array or empty' ); } if ( is_array( $argument ) ) { if ( !isset( $argument[0] ) || !is_string( $argument[0] ) || empty( $argument[0] ) ) { throw new ScriptReorganizer_Factory_Exception( 'Array argument(s) either not of type string or empty' ); } $argument = $argument[0]; } $classes[] = $argument; } $classes[0] = 'ScriptReorganizer_Type_' . $classes[0]; $classes[1] = 'ScriptReorganizer_Strategy_' . $classes[1]; for ( $i = 2, $j = count( $classes ); $i < $j; $i++ ) { $classes[$i] = 'ScriptReorganizer_Type_Decorator_' . $classes[$i]; } $root = realpath( dirname( __FILE__ ) . '/..' ) . DIRECTORY_SEPARATOR; foreach ( $classes as $class ) { @include_once $root . str_replace( '_', DIRECTORY_SEPARATOR, $class ) . '.php'; if ( !class_exists( $class ) ) { throw new ScriptReorganizer_Factory_Exception( 'Class ' . $class . ' not found' ); } } return $classes; } } interface ScriptReorganizer_Strategy { public function reformat( & $content, $eol ); } abstract class ScriptReorganizer_Type { public function __construct( ScriptReorganizer_Strategy $strategy ) { $this->strategy = $strategy; } public function __destruct() { unset( $this->strategy ); } public function load( $file ) { $content = @file_get_contents( $file ); if ( false === $content ) { throw new ScriptReorganizer_Type_Exception( 'File ' . $file . ' is not readable' ); } $eol = $this->getEolIdentifier( $content ); $this->initializeIdentifiers( $eol ); if ( $eol != $this->endOfLine ) { $content = str_replace( $eol, $this->endOfLine, $content ); } if ( preg_match( $this->hashBangIdentifier, $content, $match ) ) { $content = str_replace( $match[0], '', $content ); if ( !$this->hashBang ) { $this->hashBang = $match[1]; } } $result = trim( $content ); $result = preg_replace( '"^<\?php"', '', $result ); $result = preg_replace( '"\?>$"', '', $result ); $this->_setContent( $result ); } public function reformat() { $content = $this->_getContent(); $this->maskHeredocs( $content ); $content = trim( $this->strategy->reformat( $content, $this->endOfLine ) ); $this->unmaskHeredocs( $content ); $this->_setContent( $content ); } public function save( $file ) { $content = $this->hashBang; $content .= '<?php' . $this->endOfLine . $this->endOfLine . $this->_getContent() . $this->endOfLine . $this->endOfLine . '?>'; if ( false === @file_put_contents( $file, $content ) ) { throw new ScriptReorganizer_Type_Exception( 'File ' . $file . ' is not writable' ); } $this->endOfLine = ''; } protected function getEolIdentifier( & $content ) { static $endOfLineIdentifiers = array( 'win' => "\r\n", 'unix' => "\n", 'mac' => "\r" ); foreach ( $endOfLineIdentifiers as $eol ) { if ( false !== strpos( $content, $eol ) ) { return $eol; } } } public function _getContent() { return $this->content; } public function _setContent( $content ) { $this->content = $content; } private function initializeIdentifiers( $eol ) { if ( !$this->endOfLine ) { $this->endOfLine = $eol; $this->hashBangIdentifier = '"^[ \t' . $eol . ']*(\#\![^' . $eol . ']+' . $eol . ')"'; $heredocs = '"([<]{3}[ \t]*(\w+)[' . $eol . ']'; $heredocs .= '(.|[' . $eol . '])+?;?)[' . $eol . ']"'; $this->heredocsIdentifier = $heredocs; } } private function maskHeredocs( & $content ) { if ( preg_match_all( $this->heredocsIdentifier, $content, $this->heredocs ) ) { $i = 0; foreach ( $this->heredocs[1] as $heredoc ) { $content = str_replace( $heredoc, '< Heredoc ' . $i++ . ' >', $content ); preg_match( '"^[<]{3}[ \t]*(\w+)"', $heredoc, $identifier ); $heredocIndent = '"[' . $this->endOfLine . ']([ \t]+)' . $identifier[1] . ';?$"'; if ( preg_match( $heredocIndent, $heredoc, $indent ) ) { $this->heredocs[1][$i-1] = str_replace( $this->endOfLine . $indent[1], $this->endOfLine, $heredoc ); } } } } private function unmaskHeredocs( & $content ) { $i = 0; foreach ( $this->heredocs[1] as $heredoc ) { $hd = '< Heredoc ' . $i++ . ' >'; $trailingSpace = false !== strpos( $content, $hd . ' ' ); $content = str_replace( $hd . ( $trailingSpace ? ' ' : '' ), $heredoc . ( $trailingSpace ? $this->endOfLine : '' ), $content ); } } private $content = ''; private $endOfLine = ''; private $hashBang = ''; private $hashBangIdentifier = ''; private $heredocs = null; private $heredocsIdentifier = ''; private $strategy = null; } class ScriptReorganizer_Strategy_Route implements ScriptReorganizer_Strategy { public function reformat( & $content, $eol ) { $multiBlankLines = '"([ \t]*[' . $eol . ']){3,}"'; $result = preg_replace( $multiBlankLines, $eol . $eol, $content ); return $result; } } class ScriptReorganizer_Strategy_Quiet implements ScriptReorganizer_Strategy { public function __construct() { $this->route = new ScriptReorganizer_Strategy_Route; } public function reformat( & $content, $eol ) { $identifiers = array( 'multiLineComments' => '"[{};,' . $eol . ']([ \t]*/\*(.|[' . $eol . '])*?\*/)"', 'singleLineComments' => '"[{};,' . $eol . ']([ \t]*//[^' . $eol . ']*)"' ); foreach ( $identifiers as $identifier ) { if ( preg_match_all( $identifier, $content, $matches ) ) { foreach ( $matches[1] as $comment ) { $content = str_replace( $comment, '', $content ); } } } return $this->route->reformat( $content, $eol ); } private $route = null; } class ScriptReorganizer_Strategy_Pack implements ScriptReorganizer_Strategy { public function __construct( $oneLiner = false ) { $this->oneLiner = $oneLiner ? true : false; $this->quiet = new ScriptReorganizer_Strategy_Quiet; } public function reformat( & $content, $eol ) { $multiSpacesAndOrTabs = '"[ \t]+"'; $result = $this->quiet->reformat( $content, $eol ); if ( $this->oneLiner ) { $result = str_replace( $eol, ' ', $result ); } else { $result = preg_replace( '"[' . $eol . ']+[ \t]+"', $eol , $result ); $result = str_replace( $eol . $eol, $eol, $result ); } $result = preg_replace( $multiSpacesAndOrTabs, ' ', $result ); return $result; } private $oneLiner = false; private $quiet = null; } abstract class ScriptReorganizer_Type_Decorator extends ScriptReorganizer_Type { public function __construct( ScriptReorganizer_Type $type ) { $this->type = $type; } public function __destruct() { unset( $this->type ); } public function load( $file ) { $this->type->load( $file ); } public function reformat() { $this->type->reformat(); } public function save( $file ) { $this->type->save( $file ); } public function _getContent() { return $this->type->_getContent(); } public function _setContent( $content ) { $this->type->_setContent( $content ); } private $type = null; } class ScriptReorganizer_Type_Library extends ScriptReorganizer_Type { public function __construct( ScriptReorganizer_Strategy $strategy ) { parent::__construct( $strategy ); $this->imports = array(); } public function load( $file ) { parent::load( $file ); $baseDirectory = realpath( dirname( $file ) ); $this->imports[] = $this->retrieveRealPath( $file, $baseDirectory ); $this->_setContent( $this->resolveImports( $baseDirectory ) ); $importException = '"< import of( file [^>]+)>"'; if ( preg_match_all( $importException, $this->_getContent(), $matches ) ) { throw new ScriptReorganizer_Type_Exception( 'Import of' . PHP_EOL . '-' . ( implode( PHP_EOL . '-', $matches[1] ) ) ); } } private function resolveImports( $baseDirectory ) { $content = $this->_getContent(); $resolvedContents = array(); $eol = $this->getEolIdentifier( $content ); $staticImport = '"(;|[' . $eol . '])(([ \t]*)(include|require)(_once)?'; $staticImport .= '[ \t]*\(?[ \t' . $eol . ']*[\'\"]([^\'\"]+)[\'\"]'; $staticImport .= '[ \t' . $eol . ']*\)?[ \t]*;)"'; if ( @preg_match_all( $staticImport, $content, $matches ) ) { $i = 0; foreach ( $matches[6] as $file ) { $resolvedContents[] = $this->resolveImports( $this->retrieveContent( $file, '_once' === $matches[5][$i++], $baseDirectory ) ); } $i = 0; foreach ( $matches[2] as $staticIdentifier ) { $indent = $matches[3][$i]; $resolvedContent = str_replace( $eol, $eol . $indent, $resolvedContents[$i++] ); $staticIdentifier = '!' . str_replace( '(', '\(', str_replace( ')', '\)', $staticIdentifier ) ) . '!'; $content = preg_replace( $staticIdentifier, $resolvedContent, $content, 1 ); } } return $content; } private function retrieveContent( $file, $importOnce, $baseDirectory ) { $realFile = $this->retrieveRealPath( $file, $baseDirectory ); if ( $importOnce ) { if ( in_array( $realFile, $this->imports ) ) { $this->_setContent( '' ); return 'will not be used'; } $this->imports[] = $realFile; } try { parent::load( $realFile ); } catch ( scriptReorganizer_Type_Exception $e ) { $file = $baseDirectory . DIRECTORY_SEPARATOR . $file; $this->_setContent( '< import of file ' . $file . ' failed >' ); } return dirname( $realFile ); } private function retrieveRealPath( $file, $baseDirectory ) { if ( !is_file( $file ) ) { $includePaths = $baseDirectory . PATH_SEPARATOR . get_include_path(); foreach ( explode( PATH_SEPARATOR, $includePaths ) as $includePath ) { $script = $includePath . DIRECTORY_SEPARATOR . $file; if ( is_file( $script ) ) { $file = $script; break; } } } return realpath( $file ); } private $imports = null; } class ScriptReorganizer_Type_Script extends ScriptReorganizer_Type { } class ScriptReorganizer_Type_Decorator_AddFooter extends ScriptReorganizer_Type_Decorator { public function __construct( ScriptReorganizer_Type $type, $footer = '' ) { if ( class_exists( 'ScriptReorganizer_Type_Decorator_Pharize', false ) ) { if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Decoration of a directly sequencing Pharize-Decorator not allowed' ); } } parent::__construct( $type ); $this->footer = null === $footer ? '' : $footer; } public function reformat( $footer = null ) { if ( null !== $footer ) { $this->footer = $footer; } if ( !is_string( $this->footer ) ) { throw new ScriptReorganizer_Type_Decorator_Exception ( 'Argument $footer for AddFooter-Decorator not of type string' ); } $content = $this->_getContent(); $eolOfContent = $this->getEolIdentifier( $content ); $eolOfFooter = $this->getEolIdentifier( $this->footer ); if ( $eolOfFooter != $eolOfContent ) { $this->footer = str_replace( $eolOfFooter, $eolOfContent, $this->footer ); } parent::reformat(); $this->_setContent( $this->_getContent() . $this->footer ); } private $footer = null; } class ScriptReorganizer_Type_Decorator_AddHeader extends ScriptReorganizer_Type_Decorator { public function __construct( ScriptReorganizer_Type $type, $header = '' ) { if ( class_exists( 'ScriptReorganizer_Type_Decorator_Pharize', false ) ) { if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Decoration of a directly sequencing Pharize-Decorator not allowed' ); } } parent::__construct( $type ); $this->header = null === $header ? '' : $header; } public function reformat( $header = null ) { if ( null !== $header ) { $this->header = $header; } if ( !is_string( $this->header ) ) { throw new ScriptReorganizer_Type_Decorator_Exception ( 'Argument $header for AddHeader-Decorator not of type string' ); } $content = $this->_getContent(); $eolOfContent = $this->getEolIdentifier( $content ); $eolOfHeader = $this->getEolIdentifier( $this->header ); if ( $eolOfHeader != $eolOfContent ) { $this->header = str_replace( $eolOfHeader, $eolOfContent, $this->header ); } parent::reformat(); $this->_setContent( $this->header . $this->_getContent() ); } private $header = null; } class ScriptReorganizer_Type_Decorator_Bcompile extends ScriptReorganizer_Type_Decorator { public function __construct( ScriptReorganizer_Type $type ) { $constraint = ''; if ( $type instanceof ScriptReorganizer_Type_Decorator_Bcompile ) { $constraint = 'Bcompile-Decorator'; } else if ( class_exists( 'ScriptReorganizer_Type_Decorator_Pharize', false ) ) { if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) { $constraint = 'Pharize-Decorator'; } } if ( $constraint ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Decoration of a directly sequencing ' . $constraint . ' not allowed' ); } if ( !extension_loaded( 'bcompiler' ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'PHP Extension bcompiler not loaded' ); } parent::__construct( $type ); } public function save( $file ) { $source = 'source.' . md5($file); @file_put_contents( $source, '<?php ' . $this->_getContent() . ' ?>' ); if ( is_file( $source ) ) { if ( $target = @fopen( $file, 'wb' ) ) { bcompiler_write_header( $target ); bcompiler_write_file( $target, $source ); bcompiler_write_footer( $target ); @fclose( $target ); } unlink( $source ); } if ( !is_file( $file ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'BCompiler file ' . $file . ' is not writable' ); } } } require_once 'PHP/Archive/' . 'Creator.php'; class ScriptReorganizer_Type_Decorator_Pharize extends ScriptReorganizer_Type_Decorator { public function __construct( ScriptReorganizer_Type $type ) { $constraint = ''; if ( $type instanceof ScriptReorganizer_Type_Decorator_Pharize ) { $constraint = 'Pharize-Decorator'; } else if ( class_exists( 'ScriptReorganizer_Type_Decorator_Bcompile', false ) ) { if ( $type instanceof ScriptReorganizer_Type_Decorator_Bcompile ) { $constraint = 'Bcompile-Decorator'; } } if ( $constraint ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Decoration of a directly sequencing ' . $constraint . ' not allowed' ); } parent::__construct( $type ); $this->files = array(); $this->magic = array(); } public function load( $source, $target, $magicRequire = false ) { parent::load( $source ); if ( !is_string( $target ) || '' == $target ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Argument $target for Pharize-Decorator either not of type string or empty' ); } $content = parent::_getContent(); $this->loadContent( $content, $target, $magicRequire ); } public function loadFiles( $files, $magicRequire = false ) { if ( !is_array( $files ) || empty( $files ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Argument $files for Pharize-Decorator either not of type array or empty' ); } foreach ( $files as $source => $target ) { $this->load( $source, $target, $magicRequire ); } } public function reformat() { foreach ( $this->files as $target => $content ) { parent::_setContent( $content ); parent::reformat(); $this->files[$target] = parent::_getContent(); } } public function save( $file, $initFile = 'index.php', $compress = false, $allowDirectAccess = false ) { $archive = new PHP_Archive_Creator( $initFile, $compress, $allowDirectAccess ); $additionErrors = array(); foreach ( $this->files as $target => $content ) { $content = '<?php ' . $content . ' ?>'; if ( !$archive->addString( $content, $target, $this->magic[$target] ) ) { $additionErrors[] = $target; } } if ( !empty( $additionErrors ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Could not add ' . PHP_EOL . '-' . implode( PHP_EOL . '- file ', $additionErrors ) . PHP_EOL . 'to PHP Archive file ' . $file ); } @$archive->savePhar( $file ); if ( !is_file( $file ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'PHP Archive file ' . $file . ' is not writable' ); } } public function _getContent() { return $this->files; } public function _setContent( $targets, $magicRequire = false ) { if ( !is_array( $targets ) || empty( $targets ) ) { throw new ScriptReorganizer_Type_Decorator_Exception( 'Argument $targets for Pharize-Decorator either not of type array or empty' ); } foreach ( $targets as $target => $content ) { $this->loadContent( $content, $target, $magicRequire ); } } private function loadContent( $content, $target, $magicRequire = false ) { $this->files[$target] = $content; $this->magic[$target] = $magicRequire; } private $files = null; private $magic = null; }

?>