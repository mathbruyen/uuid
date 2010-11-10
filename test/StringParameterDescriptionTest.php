<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the string parameter description
 *
 * PHP version 5
 *
 * Copyright (c) 2010, Mathieu Bruyen <code@mais-h.eu>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Mathieu Bruyen nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     File available since Release 1.0
 */

// Set the error handling to the maximum
ini_set('error_reporting', E_ALL | E_STRICT);

// Insert the tested class
require_once realpath(__DIR__) . '/../requirements/StringParameterDescription.php';

/**
 * Testing set for the string parameter description
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Class available since Release 1.0
 * @covers UUID_StringParameterDescription
 */
class StringParameterDescriptionTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test that method check returns false for non string
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testValueNotString()
    {
        $parameterDescription = new UUID_StringParameterDescription();
        $this->assertFalse($parameterDescription->check(10));
    }
    
    /**
     * Test that method setMinLength does not accept non int
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntMin()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMinLength('bla');
    }
    
    /**
     * Test that method setMaxLength do not accept values lower than 0
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testMaxLowerThanZero()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMaxLength(-10);
    }
    
    /**
     * Test that method setMaxLength does not accept non int
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntMax()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMaxLength('bla');
    }
    
    /**
     * Test that method setValues does not accept non array
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotArrayValues()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setValues('bla');
    }
    
    /**
     * Test that method setValues does not accept non string in the array
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntInArrayValues()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setValues(array('bla', 'foo', 3, 'bar'));
    }
    
    /**
     * Test that method setMinLength does not accept an int greater than max
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testMinLowerThanMax()
    {
        $max = 10;
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMaxLength($max);
        $parameterDescription->setMinLength($max + 1);
    }
    
    /**
     * Test that method setMaxLength does not accept an int lower than min
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testMaxGreaterThanMin()
    {
        $min = 10;
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMinLength($min);
        $parameterDescription->setMaxLength($min - 1);
    }
    
    /**
     * Test that method check return true only for values of length between min and
     * max
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAllowedBetweenMinAndMax()
    {
        $min = 10;
        $max = 20;
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setMinLength($min);
        $parameterDescription->setMaxLength($max);
        
        $this->assertFalse($parameterDescription->check(str_repeat('a', $min - 1)));
        for ($i = $min; $i <= $max; $i++) {
            $this->assertTrue($parameterDescription->check(str_repeat('a', $i)));
        }
        $this->assertFalse($parameterDescription->check(str_repeat('a', $max + 1)));
    }
    
    /**
     * Test that method check return true only for values in the array
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAllowedInArray()
    {
        $values = array('bla', 'foo');
        $forbidden = 'bar';
        $parameterDescription = new UUID_StringParameterDescription();
        $parameterDescription->setValues($values);
        
        $this->assertEquals(false, $parameterDescription->check($forbidden));
        foreach ($values as $value) {
            $this->assertTrue($parameterDescription->check($value));
        }
    }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>