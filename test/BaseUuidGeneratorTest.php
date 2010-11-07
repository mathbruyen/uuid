<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the base UUIDs generator class through the mock version
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
require_once realpath(dirname(__FILE__)) . '/../generator/MockUuidGenerator.php';

// Insert the requirements class
require_once realpath(dirname(__FILE__)) . '/../requirements/UuidRequirements.php';

// Insert the capacities class
require_once realpath(dirname(__FILE__))
    . '/../requirements/GeneratorCapacities.php';

// Insert the integer parameter descriptions class
require_once realpath(dirname(__FILE__))
    . '/../requirements/IntegerParameterDescription.php';

/**
 * Testing set for the base Uuid generator
 * 
 * It uses the mock class to test it.
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
class BaseUuidGeneratorTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test that tags are actually added to the capacities
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAddTag()
    {
        $g = new UUID_MockUuidGenerator();
        
        $r = new UUID_UuidRequirements();
        $r->addTag('bla');
        
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r));
        
        $g->addTag('bla');
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r));
    }
    
    /**
     * Test that parameters added without specifying are required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAddParameterAutomatic()
    {
        $g = new UUID_MockUuidGenerator();
        
        $r1 = new UUID_UuidRequirements();
        $r1->addParameter('bla', 10);
        
        $r2 = new UUID_UuidRequirements();
        
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r1));
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r2));
        
        $pd = new UUID_IntegerParameterDescription();
        $g->addParameter('bla', $pd);
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r1));
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r2));
    }
    
    /**
     * Test that parameters added with required are required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAddParameterRequired()
    {
        $g = new UUID_MockUuidGenerator();
        
        $r1 = new UUID_UuidRequirements();
        $r1->addParameter('bla', 10);
        
        $r2 = new UUID_UuidRequirements();
        
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r1));
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r2));
        
        $pd = new UUID_IntegerParameterDescription();
        $g->addParameter('bla', $pd, true);
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r1));
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r2));
    }
    
    /**
     * Test that parameters added with unrequired are not required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAddParameterUnrequired()
    {
        $g = new UUID_MockUuidGenerator();
        
        $r1 = new UUID_UuidRequirements();
        $r1->addParameter('bla', 10);
        
        $r2 = new UUID_UuidRequirements();
        
        $this->assertFalse($g->getCapacities()->fulfillRequirements($r1));
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r2));
        
        $pd = new UUID_IntegerParameterDescription();
        $g->addParameter('bla', $pd, false);
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r1));
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r2));
    }
    
    /**
     * Test that capacities given in constructor are used
     * 
     * The tag used in requirements has been set in the instance given to the
     * constructor.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testCapacitiesInConstructor()
    {
        $c = new UUID_GeneratorCapacities();
        $c->addTag('bla');
        
        $g = new UUID_MockUuidGenerator($c);
        
        $r = new UUID_UuidRequirements();
        $r->addTag('bla');
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r));
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