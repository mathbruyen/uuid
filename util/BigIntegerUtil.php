<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class utility manipulation of arbitrary length integers
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

// Load Math_BigInteger package
require_once 'Math/BigInteger.php';

//Load exception class
require_once realpath(dirname(__FILE__)) . '/../util/Exception.php';

/**
 * Class helping manipulation of arbitrary length integers
 *
 * The arbitrary length integers are instances of Math_BigInteger but this
 * class defines some functions to help their manipulation toward use in
 * UUIDs.
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

class UUID_BigIntegerUtil
{

    /**
     * Returns a slice of bits from an integer
     * 
     * The slice of integers defined by an offset and possibly a length.
     * 
     * If the length is specified, the slice is composed of bits starting at bit
     * n°offset (bits start at 0) and of the given length, or to the highest if
     * the integer is too small.
     * <code>
     * $i = new Math_BigInteger('101100111000', 2);
     * 
     * $s = UUID_BigInteger::extractSlice($i, 5, 4);
     * echo $s->toBits();//echo "1001";
     * </code>
     * 
     * If the length is not specified, the slice is composed of the bits from bit
     * n°offset to the highest.
     * <code>
     * $i = new Math_BigInteger('101100111000', 2);
     * 
     * $s = UUID_BigInteger::extractSlice($i, 5);
     * echo $s->toBits();//echo "1011001";
     * </code>
     *
     * @param Math_BigInteger $integer the integer the slices is extracted from
     * @param int             $offset  the bit number to start with
     * @param int             $length  the number of bits to keep. All leading
     *                                  bits will be kept if not specified.
     * 
     * @return UUID_BigInteger the integer defined by the slice of bits
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public static function extractSlice($integer, $offset, $length = null)
    {
        $i = $integer->bitwise_rightShift($offset);
        if ($length !== null) {
            $i = $i->bitwise_and(new Math_BigInteger(str_repeat('1', $length), 2));
        }
        return $i;
    }
    
    /**
     * Sets arbitrary bits in a copy of the integer and returns it
     * 
     * A copy of the given integer is returned, where bits have been modified to
     * reflect those given. The the index of highest bit modified (indexes start at
     * 0) is the one given in parameters.
     * <code>
     * $i = new Math_BigInteger('101100111000', 2);
     * $value = '101';
     * 
     * $m = UUID_BigIntegerUtil::setBits($i, $value, 6);
     * echo $m->toBits(), "\n";//echo "101101011000"
     * </code>
     * 
     * If the integer is too short, 0s are padded left before the value is
     * inserted:
     * <code>
     * $i = new Math_BigInteger('11', 2);
     * $value = '101';
     * 
     * $m = UUID_BigIntegerUtil::setBits($i, $value, 8);
     * echo $m->toBits(), "\n";//echo "101000011"
     * </code>
     * 
     * If the value is too large to fit into the remaining bits an exception is
     * throwed.
     * <code>
     * $i = new Math_BigInteger('101100111000', 2);
     * $value = 10100000);
     * 
     * $m = UUID_BigIntegerUtil::setBits($i, $value, 6);
     * //UUID_Exception throwed
     * </code>
     *
     * @param Math_BigInteger $integer       the original integer that will be
     *                                          copied and have bits modified
     * @param string          $bitsSet       the bits inserted given in a binary
     *                                          string
     * @param int             $highBitNumber the index of the highest bit
     *                                          (indexes start at 0) that is
     *                                          modified
     * 
     * @return UUID_BigInteger a copy of the integer with corresponding bits
     *                          modified
     * @throws UUID_Exception in case the number of bits in value is larger
     *                          than the index where to start inserting bits
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public static function setBits($integer, $bitsSet, $highBitNumber)
    {
        // Minimum number of bits that should be present (bits start at 0)
        $minBitCount = $highBitNumber + 1;
        
        // Convert the current representation in an array of bits containing
        // enough bits
        $resultBitArray = str_split(
            str_pad(
                $integer->toBits(),
                $minBitCount,
                '0',
                STR_PAD_LEFT
            )
        );
        
        // The position in the array where the highest bit of value set will be
        // inserted
        $startPosition = count($resultBitArray) - $minBitCount;
        
        // Assert that the value is not too large compared to the high bit
        // number
        $valueBitArray = str_split($bitsSet);
        if (count($valueBitArray) > $minBitCount) {
            throw new UUID_Exception(
                'High bit number is smaller than bit number in value');
        }
        
        // Insert the bits
        for ($i = 0; $i < count($valueBitArray); $i++) {
            $resultBitArray[$startPosition + $i] = $valueBitArray[$i];
        }
        
        // Return the new integer
        return new Math_BigInteger(implode($resultBitArray), 2);
    }
    
    /**
     * Converts a BigInteger to a hex string with a fixed length
     * 
     * The toHex method from parent is called and the result is modified
     * accordingly.
     * 
     * If the string has the correct length it is simply returned:
     * <code>
     * $i = new Math_BigInteger('0xabcd', 16);
     * echo UUID_BigIntegerUtil::toHexWithFixedLength($i, 4), "\n";
     * //echo "abcd"
     * </code>
     * 
     * If the string is too short 0s are padded left:
     * <code>
     * $i = new Math_BigInteger('0xabcd', 16);
     * echo UUID_BigIntegerUtil::toHexWithFixedLength($i, 8), "\n";
     * //echo "0000abcd"
     * </code>
     * 
     * If the string is too long an exception is throwed:
     * <code>
     * $i = new Math_BigInteger('0xabcd', 16);
     * UUID_BigIntegerUtil::toHexWithFixedLength($i, 2);//UUID_Exception throwed
     * </code>
     * 
     * @param Math_BigInteger $integer the integer that is to be converted in
     *                                  string
     * @param int             $length  the expected length of the hexadecimal
     *                                  string
     * 
     * @return string an hexadecimal representation of the integer of the
     *                      requested length
     * @throws UUID_Exception in case the number of bits in value is larger than
     *                          the index where to start inserting bits
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public static function toHexWithFixedLength($integer, $length)
    {
        $hex = $integer->toHex();
        if (strlen($hex) > $length) {
            throw new UUID_Exception(
                'The integer is too large to fit in this length'
            );
        }
        return str_pad($hex, $length, '0', STR_PAD_LEFT);
    }
    
    /**
     * Converts a BigInteger to a binary string with a fixed length
     * 
     * The toBits method from parent is called and the result is modified
     * accordingly.
     * 
     * If the string has the correct length it is simply returned:
     * <code>
     * $i = new Math_BigInteger('101100', 2);
     * echo UUID_BigIntegerUtil::toBitsWithFixedLength($i, 6), "\n";
     * //echo "101100"
     * </code>
     * 
     * If the string is too short 0s are padded left:
     * <code>
     * $i = new Math_BigInteger('101100', 2);
     * echo UUID_BigIntegerUtil::toBitsWithFixedLength($i, 10), "\n";
     * //echo "0000101100"
     * </code>
     * 
     * If the string is too long an exception is throwed:
     * <code>
     * $i = new Math_BigInteger('101100', 2);
     * UUID_BigIntegerUtil::toBitsWithFixedLength($i, 2);
     * //UUID_Exception throwed
     * </code>
     *
     * @param Math_BigInteger $integer the integer that is to be converted in
     *                                  string
     * @param int             $length  the expected length of the bit string
     * 
     * @return string a binary representation of the integer of the requested
     *                  length
     * @throws UUID_Exception in case the number of bits in value is larger than
     *                          the index where to start inserting bits
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public static function toBitsWithFixedLength($integer, $length)
    {
        $bits = $integer->toBits();
        if (strlen($bits) > $length) {
            throw new UUID_Exception(
                'The integer is too large to fit in this length'
            );
        }
        return str_pad($bits, $length, '0', STR_PAD_LEFT);
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
    public static function generateRandomInteger($bitNumber)
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