<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the requirements library
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
require_once realpath(__DIR__ . '/../requirements/RequirementsLibrary.php');

// Insert the uuid requirements class
require_once realpath(__DIR__ . '/../requirements/UuidRequirements.php');

// Insert the generator capacities class
require_once realpath(__DIR__ . '/../requirements/GeneratorCapacities.php');

/**
 * Testing set for the requirements library
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Class available since Release 1.0
 * @covers    UUID_RequirementsLibrary
 */
class RequirementsLibraryTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test size requirements with no parameters
     * 
     * All values for size are accepted, size parameter is required by default.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsNoParameters()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        
        UUID_RequirementsLibrary::allowSize($gc);
        
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'If not specified, the size parameter is required'
        );
        
        UUID_RequirementsLibrary::requestSize($r, 80);
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Requirements have size parameter'
        );
    }
    
    /**
     * Test that negative size requirement is rejected
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsNegativeRejected()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        
        UUID_RequirementsLibrary::allowSize($gc);
        UUID_RequirementsLibrary::requestSize($r, -10);
        
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'Negative length is never allowed'
        );
    }
    
    /**
     * Test size requirements with not required parameter
     * 
     * Requirements without size are accepted as well as with.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsNotRequired()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'required' => false,
        );
        
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'The size parameter is not required so empty requirements are accepted'
        );
        
        UUID_RequirementsLibrary::requestSize($r, 80);
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Requirements have size parameter'
        );
    }
    
    /**
     * Test size requirements with required parameter
     * 
     * Requirements without size are rejected.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsRequired()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'required' => true,
        );
        
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'The size parameter is required'
        );
        
        UUID_RequirementsLibrary::requestSize($r, 80);
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Requirements have size parameter'
        );
    }
    
    /**
     * Test size requirements with minimum size required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsMinimum()
    {
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'min' => 10,
        );
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $r1 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r1, 5);
        $this->assertFalse(
            $gc->fulfillRequirements($r1),
            'Size parameter too short must be rejected'
        );
        
        $r2 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r2, 50);
        $this->assertTrue(
            $gc->fulfillRequirements($r2),
            'Size parameter large enough is accepted'
        );
    }
    
    /**
     * Test that negative minimum is removed
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsNegativeMinimum()
    {
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'min' => -10,
        );
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r, -5);
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'Negative minimum has been overwritten'
        );
    }
    
    /**
     * Test size requirements with maximum size required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsMaximum()
    {
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'max' => 10,
        );
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $r1 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r1, 50);
        $this->assertFalse(
            $gc->fulfillRequirements($r1),
            'Size parameter too large must be rejected'
        );
        
        $r2 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r2, 5);
        $this->assertTrue(
            $gc->fulfillRequirements($r2),
            'Size parameter short enough is accepted'
        );
    }
    
    /**
     * Test size requirements with possible values specified
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeRequirementsValues()
    {
        $accepted = array(1, 2, 4);
        $forbidden = 3;
        
        $gc = new UUID_GeneratorCapacities();
        $parameters = array(
            'values' => $accepted,
        );
        UUID_RequirementsLibrary::allowSize($gc, $parameters);
        
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r, $forbidden);
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'Size parameter not in possible values must be rejected'
        );
        foreach ($accepted as $value) {
            $r = new UUID_UuidRequirements();
            UUID_RequirementsLibrary::requestSize($r, $value);
            $this->assertTrue(
                $gc->fulfillRequirements($r),
                'Value in the array must be accepted'
            );
        }
    }
    
    /**
     * Test that the size extracted is the one given originally
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeExtracted()
    {
        $size = 100;
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestSize($r, $size);
        $this->assertEquals(
            $size,
            UUID_RequirementsLibrary::extractSize($r),
            'The size extracted must be the one set originally'
        );
    }
    
    /**
     * Test that an exception is throwed if the size was not originally set
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSizeExtractedNoSizeProvided()
    {
        $this->setExpectedException('UUID_Exception');
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::extractSize($r);
    }
    
    /**
     * Test name based requirements with no parameters
     * 
     * By default name is required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNameRequirementsNoParameters()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        
        UUID_RequirementsLibrary::allowName($gc);
        
        $this->assertFalse(
            $gc->fulfillRequirements($r),
            'The name parameter is required'
        );
        
        UUID_RequirementsLibrary::requestName($r, 'bla');
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Requirements have name parameter'
        );
    }
    
    /**
     * Test name requirements with not required parameter
     * 
     * Requirements without name are accepted as well as with.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNameRequirementsNotRequired()
    {
        $r = new UUID_UuidRequirements();
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowName($gc, false);
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Name parameter is not required'
        );
        
        UUID_RequirementsLibrary::requestName($r, 'bla');
        
        $this->assertTrue(
            $gc->fulfillRequirements($r),
            'Name is provided'
        );
    }
    
    /**
     * Test that the name extracted is the one given originally
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNameExtracted()
    {
        $name = 'bla';
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestName($r, $name);
        $this->assertEquals(
            $name,
            UUID_RequirementsLibrary::extractName($r),
            'The name extracted must be the one set originally'
        );
    }
    
    /**
     * Test that an exception is throwed if the name was not originally set
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNameExtractedNoNameProvided()
    {
        $this->setExpectedException('UUID_Exception');
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::extractName($r);
    }
    
    /**
     * Test that an unguessable generator accepts both guessable and unguessable
     * generation
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testUnguessableGenerator()
    {
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowUnguessable($gc);
        
        $r = new UUID_UuidRequirements();
        $this->assertTrue($gc->fulfillRequirements($r));
        
        UUID_RequirementsLibrary::requestUnguessable($r);
        $this->assertTrue($gc->fulfillRequirements($r));
    }
    
    /**
     * Test that an non-unguessable generator does not accept unguessable generation
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testUnguessableRequirements()
    {
        $gc = new UUID_GeneratorCapacities();
        
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestUnguessable($r);
        $this->assertFalse($gc->fulfillRequirements($r));
    }
    
    /**
     * Test that an RFC4122 generator with no version accepts both RFC4122 and
     * non-RFC4122 generation
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122GeneratorNoVersion()
    {
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowRfc4122($gc);
        
        $r = new UUID_UuidRequirements();
        $this->assertTrue($gc->fulfillRequirements($r));
        
        UUID_RequirementsLibrary::requestRfc4122($r);
        $this->assertTrue($gc->fulfillRequirements($r));
    }
    
    /**
     * Test that an non-RFC4122 generator does not accept RFC4122 generation
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122RequirementsNoVersion()
    {
        $gc = new UUID_GeneratorCapacities();
        
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestRfc4122($r);
        $this->assertFalse($gc->fulfillRequirements($r));
    }
    
    /**
     * Test that an RFC4122 generator with a version accepts both RFC4122 with and
     * without version and non-RFC4122 generation
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122GeneratorVersion()
    {
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowRfc4122($gc, 4);
        
        $r1 = new UUID_UuidRequirements();
        $this->assertTrue($gc->fulfillRequirements($r1));
        
        UUID_RequirementsLibrary::requestRfc4122($r1);
        $this->assertTrue($gc->fulfillRequirements($r1));
        
        $r2 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestRfc4122($r2, 4);
        $this->assertTrue($gc->fulfillRequirements($r2));
        
        $r3 = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestRfc4122($r3, 1);
        $this->assertFalse($gc->fulfillRequirements($r3));
    }
    
    /**
     * Test that RFC4122 tag for capacities only accept integer versions
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122CapacitiesTagNoInt()
    {
        $this->setExpectedException('UUID_Exception');
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowRfc4122($gc, 'bla');
    }
    
    /**
     * Test that RFC4122 tag for requirements only accept integer versions
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122RequirementsTagNoInt()
    {
        $this->setExpectedException('UUID_Exception');
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestRfc4122($r, 'bla');
    }
    
    /**
     * Test that RFC4122 tag for capacities do not accept negative versions
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122CapacitiesTagNegative()
    {
        $this->setExpectedException('UUID_Exception');
        $gc = new UUID_GeneratorCapacities();
        UUID_RequirementsLibrary::allowRfc4122($gc, -4);
    }
    
    /**
     * Test that RFC4122 tag for requirements do not accept negatiove versions
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122RequirementsTagNegative()
    {
        $this->setExpectedException('UUID_Exception');
        $r = new UUID_UuidRequirements();
        UUID_RequirementsLibrary::requestRfc4122($r, -4);
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