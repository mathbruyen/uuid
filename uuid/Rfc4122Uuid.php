<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class used to represent a RFC4122 UUID
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

//Load interface
require_once realpath(dirname(__FILE__)) . '/Uuid.php';

//Load exception
require_once realpath(dirname(__FILE__)) . '/../util/Exception.php';

//Load arbitrary length class
require_once realpath(dirname(__FILE__)) . '/../util/BigIntegerUtil.php';

/**
 * Class representing a RFC4122 UUID
 *
 * The class respects the definition of a UUID defined by RFC4122. It has the
 * basic methods from the Uuid interface and a method to get its version. It
 * is initialized with 3 arbitrary length integers and one normal integer. The
 * names of these integers may not be relevant in all versions but are kept as
 * it to reflect RFC4122.
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
class UUID_Rfc4122Uuid implements UUID_Uuid
{
    
    /**
     * The version of a time based UUID
     *
     * @var int
     */
    const VERSION_TIME_BASED = 1;
    
    /**
     * The version of a DCE security UUID
     *
     * @var int
     */
    const VERSION_DCE_SECURITY = 2;
    
    /**
     * The version of a name based UUID, with md5 hashing
     *
     * @var int
     */
    const VERSION_NAME_BASED_MD5 = 3;
    
    /**
     * The version of a random UUID
     *
     * @var int
     */
    const VERSION_RANDOM = 4;
    
    /**
     * The version of a name based UUID, with sha1 hashing
     *
     * @var int
     */
    const VERSION_NAME_BASED_SHA1 = 5;
    
    /**
     * The number of bits in the timestamp
     *
     * @var int
     */
    const TIMESTAMP_BIT_NUMBER = 60;
    
    /**
     * The number of bits in the clock sequence
     *
     * @var int
     */
    const CLOCK_SEQUENCE_BIT_NUMBER = 14;
    
    /**
     * The number of bits in the node identifier
     *
     * @var int
     */
    const NODE_ID_BIT_NUMBER = 48;
    
    /**
     * The size of the resulting raw integer
     *
     * @var int
     */
    const INTEGER_SIZE = 128;
    
    /**
     * The variant/layout of UUIDs represented in this class
     * 
     * This is a binary string representing the variant, or in other terms the
     * layout, of the UUID.
     * 
     * @var string
     */
    const LAYOUT = '10';
    
    /**
     * The delimiter used between blocks of hex characters in string representation
     * 
     * @var string
     */
    const BLOCK_DELIMITER = '-';
    
    /**
     * The timestamp associated with the UUID
     *
     * @var Math_BigInteger
     * @access private
     */
    private $_timestamp;
    
    /**
     * The version associated with the UUID
     *
     * @var int
     * @access private
     */
    private $_version;
    
    /**
     * The clock sequence associated with the UUID
     *
     * @var Math_BigInteger
     * @access private
     */
    private $_clockSequence;
    
    /**
     * The node identifier associated with the UUID
     *
     * @var Math_BigInteger
     * @access private
     */
    private $_nodeId;
    
    /**
     * The cached string representation of the UUID
     *
     * The string representation is built only one time and then retrieved
     * through this property.
     *
     * @var string
     * @access private
     */
    private $_stringRepresentation = null;
    
    /**
     * The cached raw integer representation of the UUID
     *
     * The raw integer representation is built only one time and then retrieved
     * through this property.
     *
     * @var Math_BigInteger
     * @access private
     */
    private $_integerRepresentation = null;
    
    /**
     * Creates a new UUID
     * 
     * Initializes with values to the given. The possible representations of the
     * UUID are built in the constructor too in order to ensure that no
     * exception can be raised in methods defined by the interface.
     * 
     * The sizes of the integers to give as the timestamp, the clock sequence and the
     * node identifier are given in class constants. The possible values for the
     * version are also given in class constants.
     * 
     * For example to generate a random UUID:
     * <code>
     * // define function: genRandomIntegerOfSize(int size) : Math_BigInteger
     * 
     * $timestamp = genRandomIntegerOfSize(UUID_Rfc4122Uuid:TIMESTAMP_BIT_NUMBER);
     * $clock = genRandomIntegerOfSize(UUID_Rfc4122Uuid:CLOCK_SEQUENCE_BIT_NUMBER);
     * $nodeId = genRandomIntegerOfSize(UUID_Rfc4122Uuid:CLOCK_SEQUENCE_BIT_NUMBER);
     * $version = UUID_Rfc4122Uuid::VERSION_RANDOM;
     * 
     * $uuid = new UUID_Rfc4122Uuid($timestamp, $clock, $nodeId, $version);
     * 
     * echo "{$uuid}", "\n";
     * echo $uuid->__toString(), "\n";
     * 
     * echo $uuid->toURN(), "\n";
     * 
     * echo $uuid->toRawInt()->toHex(), "\n";
     * </code>
     * 
     * @param Math_BigInteger $timestamp     the timestamp
     * @param Math_BigInteger $clockSequence the clock sequence
     * @param Math_BigInteger $nodeId        the node identifier
     * @param int             $version       the version
     * 
     * @return void
     * @throws UUID_Exception if anything is incorrect in parameters
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER
     * @see UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER
     * @see UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER
     * @see UUID_Rfc4122Uuid::VERSION_TIME_BASED
     * @see UUID_Rfc4122Uuid::VERSION_DCE_SECURITY
     * @see UUID_Rfc4122Uuid::VERSION_NAME_BASED_MD5
     * @see UUID_Rfc4122Uuid::VERSION_RANDOM
     * @see UUID_Rfc4122Uuid::VERSION_NAME_BASED_SHA1
    */
    public function __construct($timestamp, $clockSequence, $nodeId, $version)
    {
        // Record data
        $this->_timestamp = $timestamp->bitwise_and(
            new Math_BigInteger(
                str_repeat('1', self::TIMESTAMP_BIT_NUMBER),
                2
            )
        );
        $this->_clockSequence = $clockSequence->bitwise_and(
            new Math_BigInteger(
                str_repeat('1', self::CLOCK_SEQUENCE_BIT_NUMBER),
                2
            )
        );
        $this->_nodeId = $nodeId->bitwise_and(
            new Math_BigInteger(
                str_repeat('1', self::NODE_ID_BIT_NUMBER),
                2
            )
        );
        
        // Check the version
        $versions = array(
            self::VERSION_TIME_BASED,
            self::VERSION_DCE_SECURITY,
            self::VERSION_NAME_BASED_MD5,
            self::VERSION_RANDOM,
            self::VERSION_NAME_BASED_SHA1,
        );
        if (!in_array($version, $versions)) {
            throw new UUID_Exception('Incorrect version used');
        }
        $this->_version = $version;
    }
    
    /**
     * Returns the variant of the UUID
     *
     * The variant corresponds to the one given in RFC4122:
     * <code>"10"</code>
     * 
     * @return string the variant of RFC4122 UUID
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getVariant()
    {
        return self::LAYOUT;
    }
    
    /**
     * Returns the string representation of the UUID
     *
     * The string representation does not include the URN scheme and
     * follows the layout defined in RFC4122. An example is
     * <code>"f81d4fae-7dec-11d0-a765-00a0c91e6bf6"</code>
     * 
     * @return string the string representation of the UUID
     *
     * @access public
     * @see UUID_Uuid::getVariant()
     * @since Method available since Release 1.0
     */
    public function __toString()
    {
        if ($this->_stringRepresentation === null) {
            $this->_stringRepresentation = $this->_makeStringRepresentation();
        }
        return $this->_stringRepresentation;
    }
    
    /**
     * Returns the URN version of the UUID
     *
     * The URN representation of the UUID is the string representation prefixed
     * by the URN scheme. An example is:
     * <code>"urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6"</code>
     *
     * @return string the URN representation of the UUID
     *
     * @access public
     * @see UUID_Uuid::__toString()
     * @since Method available since Release 1.0
     */
    public function toURN()
    {
        return "urn:uuid:{$this}";
    }
    
    /**
     * Returns a raw integer representation of the UUID
     * 
     * The raw integer is returned as a Math_BigInteger due to its
     * excected large width. Math_BigInteger comes from the PEAR package
     * of the same name. The size of this integer is 128.
     * <code>
     * echo $uuid->toRawInt()->toHex();
     * //echo "f81d4fae7dec11d0a76500a0c91e6bf6"
     * </code>
     *
     * @return Math_BigInteger the integer representing the UUID
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_Rfc4122Uuid::getRawIntBitNumber()
     */
    public function toRawInt()
    {
        if ($this->_integerRepresentation === null) {
            $this->_integerRepresentation = $this->_makeIntegerRepresentation();
        }
        return $this->_integerRepresentation;
    }
    
    /**
     * Returns the version used to generate this UUID
     * 
     * The version can corresponds to the time based, to the one from DCE
     * security, to the named based, either with md5 or sha1 hashing, or to the
     * random one.
     *
     * @return int the version used to generate the UUID
     * 
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_Rfc4122Uuid::VERSION_TIME_BASED
     * @see UUID_Rfc4122Uuid::VERSION_DCE_SECURITY
     * @see UUID_Rfc4122Uuid::VERSION_NAME_BASED_MD5
     * @see UUID_Rfc4122Uuid::VERSION_RANDOM
     * @see UUID_Rfc4122Uuid::VERSION_NAME_BASED_SHA1
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Returns the size of the raw integer of the UUID
     * 
     * Indicates the size of the raw integer in number of bits
     * to know if it fit where you need them. For RFC4122 this is 128
     *
     * @return int the size of the raw integer in number of bits
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_Rfc4122Uuid::INTEGER_SIZE
     */
    public function getRawIntBitNumber()
    {
        return self::INTEGER_SIZE;
    }
    
    /**
     * Makes the string representation of this UUID.
     *
     * Makes and returns the string representation of this UUID, following
     * RFC4122. An example is
     * <code>"urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6"</code>
     *
     * @return string the string representation of this UUID
     * @throws UUID_Exception in case an integer do not fit into the accorded space
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _makeStringRepresentation()
    {
        $values = array(
            UUID_BigIntegerUtil::toHexWithFixedLength(
                $this->_getTimeLow(),
                8
            ),
            UUID_BigIntegerUtil::toHexWithFixedLength(
                $this->_getTimeMid(),
                4
            ),
            UUID_BigIntegerUtil::toHexWithFixedLength(
                $this->_getTimeHighAndVersion(),
                4
            ),
            UUID_BigIntegerUtil::toHexWithFixedLength(
                $this->_getClockSeqAndVariant(),
                4
            ),
            UUID_BigIntegerUtil::toHexWithFixedLength(
                $this->_getNodeId(),
                12
            ),
        );
        return implode(self::BLOCK_DELIMITER, $values);
    }
    
    /**
     * Makes the raw integer representation of this UUID.
     *
     * Makes and returns the raw representation of this UUID. Here it simply glue
     * together the integers that are glued with the minus character in the string
     * representation.
     *
     * @return Math_BigInteger the raw integer representation of this UUID
     * @throws UUID_Exception in case an integer do not fit into the accorded space
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _makeIntegerRepresentation()
    {
        $bits = array(
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                $this->_getTimeLow(),
                32
            ),
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                $this->_getTimeMid(),
                16
            ),
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                $this->_getTimeHighAndVersion(),
                16
            ),
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                $this->_getClockSeqAndVariant(),
                16
            ),
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                $this->_getNodeId(),
                48
            ),
        );
        return new Math_BigInteger(implode($bits), 2);
    }
    
    /**
     * Returns the time low field of the UUID
     * 
     * Return an integer composed of the slice of bits from the timestamp that
     * corresponds to the time low field.
     *
     * @return Math_BigInteger the time low field of the UUID
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getTimeLow()
    {
        return UUID_BigIntegerUtil::extractSlice($this->_timestamp, 0, 32);
    }
    
    /**
     * Returns the time mid field of the UUID
     * 
     * Return an integer composed of the slice of bits from the timestamp that
     * corresponds to the time mid field.
     * 
     * @return Math_BigInteger the time mid field of the UUID
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getTimeMid()
    {
        return UUID_BigIntegerUtil::extractSlice($this->_timestamp, 32, 16);
    }
    
    /**
     * Returns the time high field multiplexed with the version
     * 
     * Return an integer composed of the slice of bits from the timestamp that
     * corresponds to the time high field, that is then multiplexed with the version.
     *
     * @return Math_BigInteger the time high part of the timestamp multiplexed with
     *                          the version
     * @throws UUID_Exception if the version is too large [possible only if the
     *                          version is larger than 15 bits]
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getTimeHighAndVersion()
    {
        return UUID_BigIntegerUtil::setBits(
            UUID_BigIntegerUtil::extractSlice($this->_timestamp, 48),
            UUID_BigIntegerUtil::toBitsWithFixedLength(
                new Math_BigInteger($this->_version),
                4
            ),
            15
        );
    }
    
    /**
     * Returns the clock sequence field multiplexed with the variant.
     * 
     * Return an integer composed of clock sequence that is then multiplexed with the
     * variant.
     *
     * @return Math_BigInteger the clock sequence multiplexed with the variant
     * @throws UUID_Exception if the variant is too large [possible only if the
     *                          variant is larger than 15 bits]
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getClockSeqAndVariant()
    {
        return UUID_BigIntegerUtil::setBits(
            $this->_clockSequence,
            self::LAYOUT,
            15
        );
    }
    
    /**
     * Returns a copy of the node identifier
     * 
     * The node identifier is returned as a copy in order to have a consistent
     * behavior with the other fields, that are not directly the internal integers
     * and that can be modified securely.
     * 
     * @return Math_BigInteger a copy of the node identifier
     *
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getNodeId()
    {
        return $this->_nodeId->copy();
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