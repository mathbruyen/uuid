<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class representing requirements of a UUID
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

/**
 * Representation of the requirements of a UUID
 *
 * This class can be used to define requirements that a generator needs to have in
 * order to generate the expected UUID. Requirements are defined through two ways:
 * tags and parameters.
 * 
 * Tags are affirmations that the generator tell they have, simply defined by
 * strings.
 * 
 * Parameters are defined by generators and all the parameters that the
 * requirements uses must be defined.
 * 
 * This class has a very simple way to be used. Adding a tag can be done at
 * initialization or after:
 * <code>
 * $r = new UUID_UuidRequirements(array(), array('foo'));
 * $r->addTag('bla');
 * 
 * $r->getTags();// array containing 'foo' and 'bla'
 * </code>
 * Parameters can be defined exactly the same way, except that they have also values
 * associated. They are given to the constructor in an array with key being their
 * names and values the related values, or one by one to the addParameter() method.
 * Values can have any type.
 * <code>
 * $r = new UUID_UuidRequirements(array('foo' => 'bla'));
 * $r->addParameter('bar', 10);
 * 
 * $r->getParameters();// array being 'foo' => 'bla' and 'bar' => 10
 * </code>
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
class UUID_UuidRequirements
{
    
    /**
     * Parameters defined and their values
     *
     * Parameter names are keys and values are the corresponding values.
     *
     * @var array
     * @access private
     */
    private $_parameters;
    
    /**
     * Tags required
     *
     * @var array
     * @access private
     */
    private $_tags;
    
    /**
     * Constructor of the requirements optionnaly taking the parameters and tags
     *
     * If provided, parameters used to generate the UUID and tags required to be
     * handled are used to initialize the corresponding values. However it is always
     * possible to define them later.
     * 
     * @param array $parameters the parameters defined, keys are names and values the
     *                              corresponding values
     * @param array $tags       the tags required
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct($parameters = array(), $tags = array())
    {
        $this->_parameters = $parameters;
        $this->_tags = array_unique($tags);
    }
    
    /**
     * Adds a parameter with its value
     *
     * @param string $name  the name of the parameter
     * @param mixed  $value the value to give to the parameter
     * 
     * @return UUID_UuidRequirements the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addParameter($name, $value)
    {
        $this->_parameters[$name] = $value;
        return $this;
    }
    
    /**
     * Returns all the parameters in an array
     *
     * The array returned contains all the parameters defined in an array, keys are
     * parameter names and values the corresponding values.
     * 
     * @return array an array containing all parameters
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getParameters()
    {
        return $this->_parameters;
    }
    
    /**
     * Adds a required tag
     * 
     * @param string $tag the tag added
     * 
     * @return UUID_UuidRequirements the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addTag($tag)
    {
        if (!in_array($tag, $this->_tags)) {
            $this->_tags[] = $tag;   
        }
        return $this;
    }
    
    /**
     * Returns all tags in an array
     *
     * All tags required are returned in an array.
     * 
     * @return array all the required tags
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getTags()
    {
        return $this->_tags;
    }
    
    /**
     * Check that these requirements comply with capacities
     *
     * It uses the method from the capacities object to compute whether it complies
     * with the capacities, and directly return the result.
     * 
     * @param GeneratorCapacities $capacities the capacities to check if requirements
     *                                          comply with
     * 
     * @return boolean <code>true</code> the requirements womplies with capacities
     *                  <code>false</code> otherwise
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_GeneratorCapacities::fulfillRequirements()
     */
    public function complyWithCapacities($capacities)
    {
        return $capacities->fulfillRequirements($this);
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