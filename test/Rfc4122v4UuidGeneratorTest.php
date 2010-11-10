<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the random RFC4122 UUIDs generator
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
require_once realpath(__DIR__) . '/../generator/Rfc4122v4UuidGenerator.php';

// Insert the requirements class
require_once realpath(__DIR__) . '/../requirements/UuidRequirements.php';

/**
 * Testing set for the random RFC4122 Uuid generator
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Class available since Release 1.0
 * @covers UUID_Rfc4122v4UuidGenerator
 */
class Rfc4122v4UuidGeneratorTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test that the uuid is a RFC4122 one
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRfc4122()
    {
        $g = new UUID_Rfc4122v4UuidGenerator();
        $r = new UUID_UuidRequirements();
        $uuid = $g->generateUuid($r);
        
        $this->assertTrue($uuid instanceof UUID_Rfc4122Uuid);
    }
    
    /**
     * Test that the generator accepts the RFC4122 tag
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAcceptTagRfc4122()
    {
        $g = new UUID_Rfc4122v4UuidGenerator();
        $r = new UUID_UuidRequirements();
        $r->addTag(UUID_Rfc4122UuidGenerator::TAG_RFC4122_UUID);
        
        $this->assertTrue($g->getCapacities()->fulfillRequirements($r));
    }
    
    /**
     * Test that the generator accepts the random RFC4122 tag
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testAcceptTagRandomRfc4122()
    {
        $g = new UUID_Rfc4122v4UuidGenerator();
        $r = new UUID_UuidRequirements();
        $r->addTag(UUID_Rfc4122v4UuidGenerator::TAG_RFC4122_RANDOM_UUID);
        
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