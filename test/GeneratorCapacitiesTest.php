<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the generator capacities
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
require_once realpath(__DIR__ . '/../requirements/GeneratorCapacities.php');

// Insert requirement class
require_once realpath(__DIR__ . '/../requirements/UuidRequirements.php');

// Insert integer parameters description
require_once realpath(__DIR__ . '/../requirements/IntegerParameterDescription.php');

/**
 * Testing set for the generator capacities
 * 
 * This set also tests UUID_UuidRequirements::complyWithCapacities() methods because
 * it is equivalent to UUID_GeneratorCapacities::fulfillRequirements() so it is
 * easier to test equality.
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Class available since Release 1.0
 * @covers UUID_GeneratorCapacities
 * @covers UUID_UuidRequirements::complyWithCapacities
 */
class GeneratorCapacitiesTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Tags used in requirements must be defined in capacities
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTagsUsed()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        
        $r->addTag('foo');
        $this->assertFalse($gc->fulfillRequirements($r));
        $this->assertFalse($r->complyWithCapacities($gc));
        
        $gc->addTag('foo');
        $this->assertTrue($gc->fulfillRequirements($r));
        $this->assertTrue($r->complyWithCapacities($gc));
    }
    
    /**
     * Tags defined in capacities are not necessarily used in requirements
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTagsNotRequired()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        
        $gc->addTag('foo');
        $this->assertTrue($gc->fulfillRequirements($r));
        $this->assertTrue($r->complyWithCapacities($gc));
    }
    
    /**
     * Parameters used in requirements must be defined in capacities
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testParameterIsDefined()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        
        $r->addParameter('bla', 10);
        $this->assertFalse($gc->fulfillRequirements($r));
        $this->assertFalse($r->complyWithCapacities($gc));
        
        $pd = new UUID_IntegerParameterDescription();
        $gc->addParameter('bla', $pd, false);
        $this->assertTrue($gc->fulfillRequirements($r));
        $this->assertTrue($r->complyWithCapacities($gc));
    }
    
    /**
     * Parameters required in capacities must be defined in requirements
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRequiredParameter()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        
        $pd = new UUID_IntegerParameterDescription();
        $gc->addParameter('bla', $pd, true);
        
        $this->assertFalse($gc->fulfillRequirements($r));
        $this->assertFalse($r->complyWithCapacities($gc));
        
        $r->addParameter('bla', 10);
        $this->assertTrue($gc->fulfillRequirements($r));
        $this->assertTrue($r->complyWithCapacities($gc));
    }
    
    /**
     * Parameters not required in capacities are not necessarily defined in
     * requirements
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNotRequiredParameter()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        
        $pd = new UUID_IntegerParameterDescription();
        $gc->addParameter('bla', $pd, false);
        
        $this->assertTrue($gc->fulfillRequirements($r));
        $this->assertTrue($r->complyWithCapacities($gc));
    }
    
    /**
     * Values for parameters in requirements must be valid for capacities
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testValueNotAcceptable()
    {
        $gc = new UUID_GeneratorCapacities();
        $r = new UUID_UuidRequirements();
        $r->addParameter('bla', 9);
        
        $pd = new UUID_IntegerParameterDescription();
        $pd->setMinValue(10);
        $gc->addParameter('bla', $pd, false);
        
        $this->assertFalse($gc->fulfillRequirements($r));
        $this->assertFalse($r->complyWithCapacities($gc));
    }
    
    /**
     * Tests that tags are returned
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTagsReturned()
    {
        $tags = array('bla', 'foo');
        $gc = new UUID_GeneratorCapacities();
        foreach ($tags as $tag) {
            $gc->addTag($tag);
        }
        
        $this->assertEquals($tags, $gc->getTags());
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