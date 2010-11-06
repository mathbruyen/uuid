<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the integer parameter description
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
require_once realpath(dirname(__FILE__)) . '/../IntegerParameterDescription.php';

/**
 * Testing set for the integer parameter description
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Class available since Release 1.0
 */
class IntegerParameterDescriptionTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test that method check returns false for non int
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testValueNotInt()
    {
        $parameterDescription = new UUID_IntegerParameterDescription();
        $this->assertEquals(false, $parameterDescription->check('bla'));
    }
    
    /**
     * Test that method setMinValue does not accept non int
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntMin()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setMinValue('bla');
    }
    
    /**
     * Test that method setMaxValue does not accept non int
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntMax()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setMaxValue('bla');
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
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setValues(1);
    }
    
    /**
     * Test that method setValues does not accept non int in the array
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotIntInArrayValues()
    {
        $this->setExpectedException('UUID_Exception');
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setValues(array(1, 2, 'bla', 3));
    }
    
    /**
     * Test that method setMinValue does not accept an int greater than max
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
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setMaxValue($max);
        $parameterDescription->setMinValue($max + 1);
    }
    
    /**
     * Test that method setMaxValue does not accept an int lower than min
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
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setMinValue($min);
        $parameterDescription->setMaxValue($min - 1);
    }
    
    /**
     * Test that method check return true only for values between min and max
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
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setMinValue($min);
        $parameterDescription->setMaxValue($max);
        
        $this->assertEquals(false, $parameterDescription->check($min - 1));
        for ($i = $min; $i <= $max; $i++) {
            $this->assertEquals(true, $parameterDescription->check($i));
        }
        $this->assertEquals(false, $parameterDescription->check($max + 1));
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
        $values = array(1, 2, 4);
        $forbidden = 3;
        $parameterDescription = new UUID_IntegerParameterDescription();
        $parameterDescription->setValues($values);
        
        $this->assertEquals(false, $parameterDescription->check($forbidden));
        foreach ($values as $value) {
            $this->assertEquals(true, $parameterDescription->check($value));
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