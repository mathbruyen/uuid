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
require_once realpath(__DIR__ . '/../uuid/Rfc4122Uuid.php');

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
 * @covers UUID_Rfc4122Uuid
 */
class Rfc4122UuidTest extends PHPUnit_Framework_TestCase
{
    
    const RFC4122_LAYOUT_REGEXP
        = "[0-9a-fA-F]{8}(-[0-9a-fA-F]{4}){3}-[0-9a-fA-F]{12}";
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
        
        $this->assertEquals(128, $uuid->getRawIntBitNumber());
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
     * Timestamp too large is silently shrinked
     * 
     * The fields are built with integers with all bits set to 1. The timestamp
     * is however of length its expected size doubled. The UUID should shrink them
     * and no exception throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testTimestampTooLarge()
    {
        $timestamp = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER * 2),
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
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
    }
    
    /**
     * Clock sequence too large is silently shrinked
     * 
     * The fields are built with integers with all bits set to 1. The clock sequence
     * is however of length its expected size doubled. The UUID should shrink it
     * and no exception throwed.
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
            str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER * 2),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
    }
    
    /**
     * Node identifier too large is silently shrinked
     * 
     * The fields are built with integers with all bits set to 1. The node identifier
     * is however of length its expected size doubled. The UUID should shrink it
     * and no exception throwed.
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
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER * 2),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
    }
    
    /**
     * Negative timestamp is silently corrected
     * 
     * The fields are built with integers with all bits set to 1, and the timestamp
     * has a minus. The UUID should take the absolute value of it and no exception
     * throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNegativeTimestamp()
    {
        $timestamp = new Math_BigInteger(
            '-' . str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER),
            2
        );
        $clockSequence = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER * 2),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
    }
    
    /**
     * Negative clock sequence is silently corrected
     * 
     * The fields are built with integers with all bits set to 1, and the clock
     * sequence has a minus. The UUID should take the absolute value of it and no
     * exception throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNegativeClockSequence()
    {
        $timestamp = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER),
            2
        );
        $clockSequence = new Math_BigInteger(
            '-' . str_repeat('1', UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER),
            2
        );
        $nodeId = new Math_BigInteger(
            str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER * 2),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
    }
    
    /**
     * Negative node identifier is silently corrected
     * 
     * The fields are built with integers with all bits set to 1, and the node
     * identifier has a minus. The UUID should take the absolute value of it and no
     * exception throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testNegativeNodeIdentifier()
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
            '-' . str_repeat('1', UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER * 2),
            2
        );
        $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
        
        try {
            $u = new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
            $u->__toString();
            $u->toURN();
            $u->toRawInt();
        } catch (UUID_Exception $e) {
            $this->fail();
        }
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
        $timestamp = UUID_BigIntegerUtil::generateRandomInteger(
            UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER
        );
        $clockSequence = UUID_BigIntegerUtil::generateRandomInteger(
            UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER
        );
        $nodeId = UUID_BigIntegerUtil::generateRandomInteger(
            UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER
        );
        return new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
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