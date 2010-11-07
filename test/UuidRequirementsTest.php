<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the uuid requirements
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

// Insert tested class
require_once realpath(dirname(__FILE__)) . '/../requirements/UuidRequirements.php';

/**
 * Testing set for the uuid requirements
 * 
 * UUID_UuidRequirements::complyWithCapacities() is not tested here, but with
 * tests related to UUID_GeneratorCapacities.
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
class UuidRequirementsTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Tags added after construction are retrieved
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTagsAdded()
    {
        $r = new UUID_UuidRequirements();
        $r->addTag('bla');
        $r->addTag('foo');
        
        $this->assertTrue(in_array('bla', $r->getTags()));
        $this->assertTrue(in_array('foo', $r->getTags()));
        $this->assertFalse(in_array('bar', $r->getTags()));
    }
    
    /**
     * Tags added at construction are retrived
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTagsInitialized()
    {
        $r = new UUID_UuidRequirements(array(), array('bla', 'foo'));
        
        $this->assertTrue(in_array('bla', $r->getTags()));
        $this->assertTrue(in_array('foo', $r->getTags()));
        $this->assertFalse(in_array('bar', $r->getTags()));
    }
    
    /**
     * Parameters added after construction are retrived with correct value
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testParametersAdded()
    {
        $r = new UUID_UuidRequirements();
        $r->addParameter('bla1', 'foo1');
        $r->addParameter('bla2', 10);
        
        $params = $r->getParameters();
        
        $this->assertTrue(array_key_exists('bla1', $params));
        $this->assertEquals('foo1', $params['bla1']);
        
        $this->assertTrue(array_key_exists('bla2', $params));
        $this->assertEquals(10, $params['bla2']);
        
        $this->assertFalse(array_key_exists('bar', $params));
    }
    
    /**
     * Parameters added at construction are retrived with correct value
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testParametersInitialized()
    {
        $r = new UUID_UuidRequirements(
            array(
                'bla1' => 'foo1',
                'bla2' => 10,
            )
        );
        
        $params = $r->getParameters();
        
        $this->assertTrue(array_key_exists('bla1', $params));
        $this->assertEquals('foo1', $params['bla1']);
        
        $this->assertTrue(array_key_exists('bla2', $params));
        $this->assertEquals(10, $params['bla2']);
        
        $this->assertFalse(array_key_exists('bar', $params));
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