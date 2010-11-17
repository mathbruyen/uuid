<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the base class for generating name based RFC4222 UUIDs
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
require_once realpath(__DIR__ . '/Rfc4122UuidGenerator.php');

// Load UUID class
require_once realpath(__DIR__ . '/../uuid/Rfc4122Uuid.php');

//Load arbitrary length class
require_once realpath(__DIR__ . '/../util/BigIntegerUtil.php');

// Load requirements library
require_once realpath(__DIR__ . '/../requirements/RequirementsLibrary.php');

/**
 * The basic class for name based RFC4122 UUID generators
 * 
 * Classes that want to implement this class must still define the version they use
 * by giving it to the constructor, and implement the hash method that returns a
 * Math_BigInteger from a string value.
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
abstract class UUID_NameRfc4122UuidGenerator extends UUID_Rfc4122UuidGenerator
{
 
    /**
     * The default namespace, if provided
     *
     * The default namespace is used for requirements that do not specify any. It can
     * be absent and in that case the generator do not accept requirements without
     * namespace.
     *
     * @var array
     * @access private
     */
    private $_namespace;
 
    /**
     * The version of generated UUIDs
     *
     * The default namespace is used for requirements that do not specify any. It can
     * be absent and in that case the generator do not accept requirements without
     * namespace.
     *
     * @var array
     * @access private
     */
    private $_version;
    
    /**
     * Initializes the parent class, version and namespace if provided
     * 
     * The default namespace is not required, and if not provided only requirements
     * specifying one will be accepted.
     * 
     * @param int    $version   the version used
     * @param string $namespace the default namespace, if any
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct($version, $namespace = null)
    {
        parent::__construct($version);
        
        $this->_version = $version;
        
        $this->_namespace = $namespace;
        $nsRequired = ($namespace === null);
        
        UUID_RequirementsLibrary::allowName($this->getCapacities(), $nsRequired);
    }
    
    /**
     * Encapsulates the algorithm for generating name based UUIDs
     * 
     * @param UUID_UuidRequirements $requirements the requirements used to generate a
     *                                              UUID
     * 
     * @return UUID_Rfc4122Uuid
     * @throws UUID_Exception if the name parameter is not provided
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function generateUuid($requirements)
    {
        $name = UUID_RequirementsLibrary::extractName($requirements);
        try {
            $namespace = UUID_RequirementsLibrary::extractNamespace($requirements);
        } catch (UUID_Exception $e) {
            $namespace = $this->_namespace;
        }
        $int = $this->hash("{$namespace}{$name}");
        
        $timestamp = UUID_BigIntegerUtil::extractSlice(
            $int,
            0,
            UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER
        );
        $clockSequence = UUID_BigIntegerUtil::extractSlice(
            $int,
            UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER,
            UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER
        );
        $nodeId = UUID_BigIntegerUtil::extractSlice(
            $int,
            UUID_Rfc4122Uuid::TIMESTAMP_BIT_NUMBER
            + UUID_Rfc4122Uuid::CLOCK_SEQUENCE_BIT_NUMBER,
            UUID_Rfc4122Uuid::NODE_ID_BIT_NUMBER
        );
        $version = $this->_version;
        
        return new UUID_Rfc4122Uuid($timestamp, $clockSequence, $nodeId, $version);
    }
    
    /**
     * Computes the has of the name used within its namespace
     * 
     * @param string $fullName the name used within its namespace
     * 
     * @return Math_BigInteger
     *
     * @access public
     * @since Method available since Release 1.0
     */
    protected abstract function hash($fullName);
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>