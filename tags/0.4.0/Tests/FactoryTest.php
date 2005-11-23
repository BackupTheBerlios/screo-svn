<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * $Id$
 */

require_once 'PHPUnit2/Framework/IncompleteTestError.php';
require_once 'PHPUnit2/Framework/TestCase.php';

require_once 'ScriptReorganizer/Factory.php';
require_once 'ScriptReorganizer/Factory/Exception.php';

class ScriptReorganizer_Tests_FactoryTest extends PHPUnit2_Framework_TestCase
{
    // {{{ public function testArgumentOfIllegalTypeException()
    
    public function testArgumentOfIllegalTypeException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', 1 );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Argument.+ not of type string"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testArgumentEmptyStringException()
    
    public function testArgumentEmptyStringException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', '' );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Argument.+ empty"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testArgumentEmptyArrayException()
    
    public function testArgumentEmptyArrayException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', array() );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Argument.+ empty"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testArgumentArrayValueOfIllegalTypeException()
    
    public function testArgumentArrayValueOfIllegalTypeException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', array( 1 ) );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Array.+ not of type string"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testArgumentEmptyArrayValueException()
    
    public function testArgumentEmptyArrayValueException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', array( '' ) );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Array.+ empty"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testStringArgumentClassNotFoundException()
    
    public function testStringArgumentClassNotFoundException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', 'AddHeaders' );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Class \w+ not found"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testArrayArgumentClassNotFoundException()
    
    public function testArrayArgumentClassNotFoundException()
    {
        try {
            $type = ScriptReorganizer_Factory::create( 'Script', 'Quiet', array( 'AddHeaders' ) );
            $this->fail( 'Exception not thrown' );
        } catch ( ScriptReorganizer_Factory_Exception $e ) {
            $this->assertRegExp( '"Class \w+ not found"', $e->getMessage() );
        }
    }
    
    // }}}
    // {{{ public function testScriptTypeObjectCreationSuccessful()
    
    public function testScriptTypeObjectCreationSuccessful()
    {
        $type = ScriptReorganizer_Factory::create( 'Script', 'Route' );
        
        $this->assertTrue( $type instanceof ScriptReorganizer_Type_Script );
    }
    
    // }}}
    // {{{ public function testArrayScriptTypeObjectCreationSuccessful()
    
    public function testArrayScriptTypeObjectCreationSuccessful()
    {
        $type = ScriptReorganizer_Factory::create( array( 'Script' ), array( 'Route' ) );
        
        $this->assertTrue( $type instanceof ScriptReorganizer_Type_Script );
    }
    
    // }}}
    // {{{ public function testAddHeaderDecoratorObjectCreationSuccessful()
    
    public function testAddHeaderDecoratorObjectCreationSuccessful()
    {
        $type = ScriptReorganizer_Factory::create( 'Script', 'Route', 'AddHeader', 'AddFooter' );
        
        $this->assertTrue( $type instanceof ScriptReorganizer_Type_Decorator_AddHeader );
    }
    
    // }}}
    // {{{ public function testArrayAddHeaderDecoratorObjectCreationSuccessful()
    
    public function testArrayAddHeaderDecoratorObjectCreationSuccessful()
    {
        $type = ScriptReorganizer_Factory::create(
            array( 'Script' ), array( 'Route' ), array( 'AddHeader' ), array( 'AddFooter' )
        );
        
        $this->assertTrue( $type instanceof ScriptReorganizer_Type_Decorator_AddHeader );
    }
    
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
