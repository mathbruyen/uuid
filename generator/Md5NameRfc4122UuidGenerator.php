<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the base class for generating md5 name based RFC4222 UUIDs
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

// Load base class
require_once realpath(__DIR__ . '/NameRfc4122UuidGenerator.php');

//Load arbitrary length class
require_once realpath(__DIR__ . '/../util/BigIntegerUtil.php');

/**
 * The basic class for md5 name based RFC4122 UUID generators
 * 
 * Simple implementation of a name based UUID generator using md5 hashing.
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
class UUID_Md5NameRfc4122UuidGenerator extends UUID_NameRfc4122UuidGenerator
{
    
    /**
     * Initializes the parent class with version and namespace if provided
     * 
     * The default namespace is not required, and if not provided only requirements
     * specifying one will be accepted. Version used corresponds to name based
     * generation with md5 algorithm.
     * 
     * @param string $namespace the default namespace, if any
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct($namespace = null)
    {
        $version = UUID_Rfc4122Uuid::VERSION_NAME_BASED_MD5;
        if ($namespace === null) {
            parent::__construct($version);
        } else {
            parent::__construct($version, $namespace);
        }
    }
    
    /**
     * Computes the has of the name used within its namespace using md5 algorithm
     * 
     * @param string $fullName the name used within its namespace
     * 
     * @return Math_BigInteger
     *
     * @access public
     * @since Method available since Release 1.0
     */
    protected function hash($fullName)
    {
        return new Math_BigInteger(md5($fullName), 16);
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