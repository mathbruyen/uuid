<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of a base class with capacities handling for generating UUIDs
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

// Load interface
require_once realpath(__DIR__) . '/UuidGenerator.php';

// Load generator capacities
require_once realpath(__DIR__) . '/../requirements/GeneratorCapacities.php';

/**
 * A basic UUID generator implementation that handles capacities
 * 
 * It implements methods related to capacities and provide a way to configure them.
 * All things related to actual UUID generation are up to the subclasses.
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
abstract class UUID_BaseUuidGenerator implements UUID_UuidGenerator
{
    
    /**
     * The capacities that the generator has
     *
     * @var UUID_GeneratorCapacities
     * @access private
     */
    private $_capacities;
    
    /**
     * Constructor.
     *
     * Initializes the capacities to the ones given in parameters or to a new one if
     * nothing is provided. This can be used to specify a different class of
     * capacities with more functionnalities.
     * 
     * @param UUID_GeneratorCapacities $capacities the instance of capacities to use.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct($capacities = null)
    {
        if ($capacities === null) {
            $this->_capacities = new UUID_GeneratorCapacities();
        } else {
            $this->_capacities = $capacities;
        }
    }

    /**
     * Informs about the capacities that the generator has
     *
     * The capacities define what the generator can do, like parameters it uses and
     * their validation, parameters that are mandatory and tags that the generator
     * fulfills.
     * 
     * @return UUID_GeneratorCapacities
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getCapacities()
    {
        return $this->_capacities;
    }
    
    /**
     * Allows a new parameter to the generator's capacities
     * 
     * Forwards the call to the capacities object.
     * 
     * @param string                    $name        the name of the parameter
     * @param UUID_ParameterDescription $description the description of allowed
     *                                                  values
     * @param boolean                   $required    whether the parameter is
     *                                                  required or not
     * 
     * @return UUID_BaseUuidGenerator the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    protected function addParameter($name, $description, $required = true)
    {
        $this->_capacities->addParameter($name, $description, $required);
        return $this;
    }
    
    /**
     * Allows a new tag to the generator's capacities
     *
     * Forwards the call to capacities object.
     * 
     * @param string $tag the tag allowed
     * 
     * @return UUID_BaseUuidGenerator the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    protected function addTag($tag)
    {
        $this->_capacities->addTag($tag);
        return $this;
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