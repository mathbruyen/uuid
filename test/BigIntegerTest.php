<?

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing the arbitrary integer extended in the package
 *
 * PHP version 5
 *
 * Copyright (c) 2002-2010, Mathieu Bruyen <code@mais-h.eu>.
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
 *   * Neither the name of Sebastian Bergmann nor the names of his
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
 * @package    UUID
 * @author     Mathieu Bruyen <code@mais-h.eu>
 * @copyright  2010 Mathieu Bruyen <code@mais-h.eu>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since      File available since Release 1.0
 */

// Set the error handling to the maximum
ini_set('error_reporting', E_ALL | E_STRICT);

// Insert the tested class
require_once(realpath(dirname(__FILE__)) . '/../BigIntegerUtil.php');

/**
 * Testing set for the arbitrary integer defined in the package
 * 
 * @package    UUID
 * @author     Mathieu Bruyen <code@mais-h.eu>
 * @copyright  2010 Mathieu Bruyen <code@mais-h.eu>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since      Class available since Release 1.0
 */
///TODO test other methods
class BigIntegerTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test the integer slicing when no length is specified
     *
     * Using this functionnality should remove the trailling bits of the
     * binary representation.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testExtractSliceNoLength()
    {
        $initBits = '1001100';
        $offset = 2;
        $expectedBits = substr($initBits, 0, -$offset);
        
        $integer = new Math_BigInteger($initBits, 2);
        $expected = new Math_BigInteger($expectedBits, 2);
        
        $this->assertTrue($expected->equals(UUID_BigIntegerUtil::extractSlice($integer, $offset)));
    }
    
    /**
     * Test the integer slicing when length is specified
     *
     * Using this functionnality should remove the trailling bits of the
     * binary representation and remove the leading bits, resulting in a bit
     * representation of the given length.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testExtractSliceWithLength()
    {
        $initBits = '1001100';
        $offset = 2;
        $length = 4;
        $expectedBits = substr($initBits, - ($offset + $length), - $offset);
        
        $integer = new Math_BigInteger($initBits, 2);
        $expected = new Math_BigInteger($expectedBits, 2);
        
        $this->assertTrue($expected->equals(UUID_BigIntegerUtil::extractSlice($integer, $offset, $length)));
    }
    
    /**
     * Test the bit insertion with normal conditions
     *
     * In the case the original integer is large enough and
     * value has smaller number of bits than the high bit the
     * bits are only inserted in the correct position in the integer.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSetBitsNormal()
    {
        $initBits = '1001100';
        $valueBits = '10';
        $highBit = 4;
        $expectedBits = '1010100';
        
        $integer = new Math_BigInteger($initBits, 2);
        $expected = new Math_BigInteger($expectedBits, 2);
        
        $this->assertTrue($expected->equals(UUID_BigIntegerUtil::setBits($integer, new Math_BigInteger($valueBits, 2), $highBit)));
    }
    
    /**
     * Test the bit insertion with passding
     *
     * In the case the original integer is not enough large some bits must
     * be padded before the new bits can be set. Value has smaller number
     * of bits than the high bit the bits are only inserted in the correct
     * position in the integer.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSetBitsPadding()
    {
        $initBits = '1001100';
        $valueBits = '10';
        $highBit = 9;
        $expectedBits = '1001001100';
        
        $integer = new Math_BigInteger($initBits, 2);
        $expected = new Math_BigInteger($expectedBits, 2);
        
        $this->assertTrue($expected->equals(UUID_BigIntegerUtil::setBits($integer, new Math_BigInteger($valueBits, 2), $highBit)));
    }
    
    /**
     * Test the bit insertion when value is too large
     *
     * If the value is too large to be put into the number
     * of bits defined by the high bit then an exception is
     * throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testSetBitsLargeValue()
    {
        $initBits = '1001100';
        $valueBits = '1011111';
        $highBit = 2;
        
        $integer = new Math_BigInteger($initBits, 2);
        
        try {
            UUID_BigIntegerUtil::setBits($integer, new Math_BigInteger($valueBits, 2), $highBit);
            $this->fail();
        } catch (UUID_Exception $e) {
            //Normal way
        }
    }
    
    /**
     * Test the toHexWithFixedLength method when the integer as the exact length
     * 
     * If the value has excatly the correct length then it is returned as is.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToHexWithFixedLengthNormal()
    {
        $initHex = 'abcd';
        $length = 4;
        $expected = 'abcd';
        
        $integer = new Math_BigInteger($initHex, 16);
        
        $this->assertEquals($expected, UUID_BigIntegerUtil::toHexWithFixedLength($integer, $length));
    }
    
    /**
     * Test the toHexWithFixedLength method when the integer is too small
     * 
     * If the value is too small 0s are to be padded left.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToHexWithFixedLengthTooShort()
    {
        $initHex = 'abcd';
        $length = 6;
        $expected = '00abcd';
        
        $integer = new Math_BigInteger($initHex, 16);
        
        $this->assertEquals($expected, UUID_BigIntegerUtil::toHexWithFixedLength($integer, $length));
    }
    
    /**
     * Test the toHexWithFixedLength method when the integer is too large
     * 
     * If the value does not fit into the given length then an exception
     * is throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToHexWithFixedLengthTooLong()
    {
        $initHex = 'abcd';
        $length = 2;
        
        $integer = new Math_BigInteger($initHex, 16);
        
        try {
            UUID_BigIntegerUtil::toHexWithFixedLength($integer, $length);
        } catch (UUID_Exception $e) {
            // Normal way
        }
    }
    
    /**
     * Test the toBitsWithFixedLength method when the integer as the exact length
     * 
     * If the value has excatly the correct length then it is returned as is.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToBitsWithFixedLengthNormal()
    {
        $initBits = '101100';
        $length = 6;
        $expected = '101100';
        
        $integer = new Math_BigInteger($initBits, 2);
        
        $this->assertEquals($expected, UUID_BigIntegerUtil::toBitsWithFixedLength($integer, $length));
    }
    
    /**
     * Test the toBitsWithFixedLength method when the integer is too small
     * 
     * If the value is too small 0s are to be padded left.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToBitsWithFixedLengthTooShort()
    {
        $initBits = '101100';
        $length = 10;
        $expected = '0000101100';
        
        $integer = new Math_BigInteger($initBits, 2);
        
        $this->assertEquals($expected, UUID_BigIntegerUtil::toBitsWithFixedLength($integer, $length));
    }
    
    /**
     * Test the toBitsWithFixedLength method when the integer is too large
     * 
     * If the value does not fit into the given length then an exception
     * is throwed.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function testToBitsWithFixedLengthTooLong()
    {
        $initBits = '101100';
        $length = 2;
        
        $integer = new Math_BigInteger($initBits, 2);
        
        try {
            UUID_BigIntegerUtil::toBitsWithFixedLength($integer, $length);
        } catch (UUID_Exception $e) {
            // Normal way
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