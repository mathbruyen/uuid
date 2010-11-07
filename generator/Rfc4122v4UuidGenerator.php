<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the base class for generating random RFC4222 UUIDs
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

// Load parent class
require_once realpath(dirname(__FILE__)) . '/Rfc4122UuidGenerator.php';

// Load uuid class
require_once realpath(dirname(__FILE__)) . '/../uuid/Rfc4122Uuid.php';

// Load Math_BigInteger package
require_once 'Math/BigInteger.php';

/**
 * Generator of random RFC4122 UUIDs
 * 
 * This generator only adds the tag for random RFC4122 UUIDs, but it also accept the
 * basic RFC4122 UUIDs tag defined by its parent class.
 * 
 * The generator can be simply used with no requirements:
 * <code>
 * $g = new UUID_Rfc4122v4UuidGenerator();
 * $r = new UUID_UuidRequirements();
 * 
 * if ($g->getCapacities()->fulfillRequirements($r)) {//will be ok
 *     $uuid = $g->generateUuid($r);
 * }
 * </code>
 * But it accepts the RFC4122 tag:
 * <code>
 * $g = new UUID_Rfc4122v4UuidGenerator();
 * $r = new UUID_UuidRequirements();
 * $r->addTag(UUID_Rfc4122UuidGenerator::TAG_RFC4122_UUID);
 * 
 * if ($g->getCapacities()->fulfillRequirements($r)) {//will be ok
 *     $uuid = $g->generateUuid($r);
 * }
 * </code>
 * And the more specific random tag:
 * <code>
 * $g = new UUID_Rfc4122v4UuidGenerator();
 * $r = new UUID_UuidRequirements();
 * $r->addTag(UUID_Rfc4122v4UuidGenerator::TAG_RFC4122_RANDOM_UUID);
 * 
 * if ($g->getCapacities()->fulfillRequirements($r)) {//will be ok
 *     $uuid = $g->generateUuid($r);
 * }
 * </code>
 * 
 * @category  Structures
 * @package   UUID
 * @author    Mathieu Bruyen <code@mais-h.eu>
 * @copyright 2010 Mathieu Bruyen <code@mais-h.eu>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since     Interface available since Release 1.0
 */
class UUID_Rfc4122v4UuidGenerator extends UUID_Rfc4122UuidGenerator
{
    
    /**
     * The tag used for random RFC4122 UUIDs
     * 
     * @var string
     */
    const TAG_RFC4122_RANDOM_UUID = 'Rfc4122_v4';
    
    /**
     * Constructor
     *
     * Adds the tag corresponding to random RFC4122 UUIDs.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->addTag(self::TAG_RFC4122_RANDOM_UUID);
    }
    
    /**
     * Generates a new UUID
     *
     * Generates a new UUID respecting the requirements or throw an exception if
     * requirements cannot be achieved.
     * 
     * If requirements comply with generator's capacities (or equivalently if
     * capacities fulfill requirements) should not throw exception. It is still
     * allowed to throw exception, but only if there is a configuration problem. If
     * an exception is thrown after capacities were checked, it thus means a
     * configuration problem and for example the factory is allowed to remove the
     * generator.
     * <code>
     * //$g = make the generator
     * //$r = make some requirements
     * 
     * $gc = $g->getCapacities();
     * if ($gc->fulfillRequirements($r)) {
     *     $uuid = $g->generateUuid($r);// if it fails, it means configuration error
     * }
     * </code>
     * 
     * @param UUID_UuidRequirements $requirements the requirements that are required
     *                                              for the UUID
     * 
     * @return UUID_Uuid a new UUID
     * @throws UUID_Exception an exception is throwed is something goes wrong
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_UuidGenerator::getCapacities()
     */
    public function generateUuid($requirements)
    {
        $timestamp = $this->_generateRandomInteger(
            UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER
        );
        $clockSequence = $this->_generateRandomInteger(
            UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER
        );
        $nodeId = $this->_generateRandomInteger(
            UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        return new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Generates a random integer with a given number bits
     *
     * Generates a random integer by building a random binary string of the given
     * size and then converting it to a Math_BigInteger.
     * 
     * @param int $size the number of bits to generate
     * 
     * @return Math_BigInteger the generated integer
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _generateRandomInteger($size)
    {
        $binaryString = '';
        for ($i = 0; $i < $size; $i++) {
            $binaryString .= mt_rand(0, 1);
        }
        return new Math_BigInteger($binaryString, 2);
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