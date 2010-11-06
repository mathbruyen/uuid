<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the RFC4122 UUIDs
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
require_once realpath(dirname(__FILE__)) . '/../Rfc4122Uuid.php';

/**
 * Testing set for the RFC4122 Uuid
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
class Rfc4122UuidTest extends PHPUnit_Framework_TestCase
{
    
    const RFC4122_LAYOUT_REGEXP = "([0-9a-fA-F]{8})-([0-9a-fA-F]{4})-([0-9a-fA-F]{4})-([0-9a-fA-F]{2})([0-9a-fA-F]{2})-([0-9a-fA-F]{12})";
    const UUID_SCHEME = 'uuid';
    
    /**
     * Test that the layout of a UUID follows RFC4122
     *
     * It builds a random UUID using constants given in the UUID class and tests it
     * against the regular expression.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testLayoutUuid()
    {
        $uuid = $this->_generateRandomUuid(UUID_Rfc4122Uuid::VERSION_RANDOM);
        $regexp = self::RFC4122_LAYOUT_REGEXP;
        
        $this->assertRegExp("#^{$regexp}$#", "{$uuid}");
    }
    
    /**
     * Test that the layout of a URN follows RFC4122
     *
     * It builds a random UUID using constants given in the UUID class and tests its
     * URN against the regular expression.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testLayoutUrn()
    {
        $uuid = $this->_generateRandomUuid(UUID_Rfc4122Uuid::VERSION_RANDOM);
        $regexp = self::RFC4122_LAYOUT_REGEXP;
        $scheme = self::UUID_SCHEME;
        
        $this->assertRegExp("#^urn:{$scheme}:{$regexp}$#", $uuid->toURN());
    }
    
    /**
     * Test that the raw int returned is not larger than the announced size
     *
     * It builds a random UUID using constants given in the UUID class and tests its
     * raw integer version against the size it gives.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRawInt()
    {
        $uuid = $this->_generateRandomUuid(UUID_Rfc4122Uuid::VERSION_RANDOM);
        
        $this->assertTrue($uuid->toRawInt() instanceof Math_BigInteger);
        
        $max = new Math_BigInteger(str_repeat('1', $uuid->getRawIntBitNumber()), 2);
        
        $this->assertTrue($uuid->toRawInt()->compare($max) <= 0);
    }
    
    /**
     * Test that the size returned is the one in the class constant
     *
     * It builds a random UUID using constants given in the UUID class and tests its
     * raw integer size agains the class constant.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testRawIntSize()
    {
        $uuid = $this->_generateRandomUuid(UUID_Rfc4122Uuid::VERSION_RANDOM);
        
        $this->assertEquals(
            $uuid->getRawIntBitNumber(),
            UUID_Rfc4122Uuid::INTEGER_SIZE
        );
    }
    
    /**
     * Test that the version returned by getVersion is correct
     *
     * The version given in the constructor must be returned.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testVersions()
    {
        $versions = array(
            UUID_Rfc4122Uuid::VERSION_TIME_BASED,
            UUID_Rfc4122Uuid::VERSION_DCE_SECURITY,
            UUID_Rfc4122Uuid::VERSION_NAME_BASED_MD5,
            UUID_Rfc4122Uuid::VERSION_RANDOM,
            UUID_Rfc4122Uuid::VERSION_NAME_BASED_SHA1,
        );
        $regexp = self::RFC4122_LAYOUT_REGEXP;
        
        foreach ($versions as $version) {
            $uuid = $this->_generateRandomUuid($version);
            $this->assertEquals($version, $uuid->getVersion());
        }
    }
    
    /**
     * Test that an incorrect version leads to an exception
     *
     * It builds a random UUID using a version to high and expects an exception.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testIncorrectVersion()
    {
        $this->setExpectedException('UUID_Exception');
        $uuid = $this->_generateRandomUuid(10);
    }
    
    /**
     * Test that the variant returned corresponds to the one from the RFC
     *
     * The variant must be "10".
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testVariant()
    {
        $uuid = $this->_generateRandomUuid(UUID_Rfc4122Uuid::VERSION_RANDOM);
        $this->assertEquals('10', $uuid->getVariant());
    }
    
    /**
     * Tries to build a UUID with too large timestamp
     * 
     * The fields are built with integers with all bits set to 1. The timestamp is
     * however of a larger length than its possible. Note that it is possible to have
     * 4 bits larger than the size acknowledged in constants because of the
     * multiplexing, bits are accepted but erased.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTimestampTooLarge()
    {
        $timestamp = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER + 5),
            2
        );
        $clockSequence = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        $this->setExpectedException('UUID_Exception');
        new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Tries to build a UUID with too large clock sequence
     * 
     * The fields are built with integers with all bits set to 1. The clock sequence
     * is however of a larger length than its possible. Note that it is possible to
     * have 2 bits larger than the size acknowledged in constants because of the
     * multiplexing, bits are accepted but erased.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testClockSequenceTooLarge()
    {
        $timestamp = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER),
            2
        );
        $clockSequence = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER + 3),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        $this->setExpectedException('UUID_Exception');
        new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Tries to build a UUID with too large node identifier
     * 
     * The fields are built with integers with all bits set to 1. The node identifier
     * is however of length its expected size plus one.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNodeIdentifierTooLarge()
    {
        $timestamp = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER),
            2
        );
        $clockSequence = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER + 1),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        $this->setExpectedException('UUID_Exception');
        new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Builds a random UUID with the given version
     *
     * @param int $version the version of the UUID created
     * 
     * @return Rfc4122Uuid the random UUID that must be of Rfc4122Uuid because it
     *          tests this class
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _generateRandomUuid($version)
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
        return new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Generates a random integer with a given number of bits
     *
     * @param int $bitNumber the number of bits in the returned integer
     * 
     * @return Math_BigInteger the integer with the requested number of bits
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _generateRandomInteger($bitNumber)
    {
        $bits = '';
        for ($i = 0; $i < $bitNumber; $i++) {
            $bits .= mt_rand(0, 1);
        }
        return new Math_BigInteger($bits, 2);
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