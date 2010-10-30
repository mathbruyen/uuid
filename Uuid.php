<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the interface used for representing a UUID
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

/**
 * Inteface of object representation of a Uuid
 *
 * Every UUID inherit from this interface. It can be retrieved as a string
 * with:
 * <code>$uuid->__toString()</code>
 * A simpler way to retrieve it as a string is to simply put the object into
 * double quotes like
 * <code>"{$uuid}"</code>
 * because of the use of this magic method.
 * 
 * It can also be retrieved as a Uniform Ressource Name (URN) with
 * <code>$uuid->toURN()</code>
 * 
 * Finally there is a way to retrive it as an integer:
 * <code>$uuid->toRawInt($base)</code>
 * It is thus represented in a string due to the expected large length of the
 * integer. This is of use in database identifiers for example. The base used
 * to display is to be chosen.
 *
 * @package    UUID
 * @author     Mathieu Bruyen <code@mais-h.eu>
 * @copyright  2010 Mathieu Bruyen <code@mais-h.eu>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.mais-h.eu/doc/index.php/UUID_php_package
 * @since      Class available since Release 1.0
 */
interface UUID_Uuid
{
    /**
     * Returns the variant of the UUID
     *
     * The variant is a string composed of 0s and 1s that corresponds to
     * the layout of the UUID. For example a UUID following RFC4122 must
     * return the variant
     * <code>"10"</code>
     * 
     * @return string the variant of the UUID
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getVariant();
    
    /**
     * Returns the string representation of the UUID
     *
     * The string representation does not include the URN scheme and
     * follows the layout related to the variant of the UUID. For
     * example a RFC4222 UUID should look like
     * <code>"f81d4fae-7dec-11d0-a765-00a0c91e6bf6"</code>
     * 
     * @return string the string representation of the UUID
     *
     * @access public
     * @see UUID_Uuid::getVariant()
     * @since Method available since Release 1.0
     */
    public function __toString();
    
    /**
     * Returns the URN version of the UUID
     *
     * The URN representation of the UUID should be the URN scheme
     * followed by the string representation of the UUID. For
     * example a RFC4222 UUID should look like
     * <code>"urn:uuid:f81d4fae-7dec-11d0-a765-00a0c91e6bf6"</code>
     *
     * @return string the URN representation of the UUID
     *
     * @access public
     * @see UUID_Uuid::__toString()
     * @since Method available since Release 1.0
     */
    public function toURN();
    
    /**
     * Returns a raw integer representation of the UUID
     * 
     * The raw integer is returned as a string due to its expected
     * length. The base used in the string representation is
     * chosen. The accepted values for the base is subject to vary
     * between implementations, but the classical 2, 10 and 16 shoud
     * be available. For example a RFC4122 UUID displayed in base 16
     * can look like
     * <code>"f81d4fae7dec11d0a76500a0c91e6bf6"</code>
     * The translation from UUID to raw integer is not documented so
     * it is up to the implementator.
     *
     * @param int $base the base used to convert the integer into a string
     * @return string the UUID represented through an integer encoded
     *              in a string
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function toRawInt($base);
    
    /**
     * Returns the size of the raw integer of the UUID
     * 
     * Indicates the size of the raw integer in number of bits
     * to know if it fit where you need them
     *
     * @return int the size of the raw integer in number of bits
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getRawIntBitNumber();
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>