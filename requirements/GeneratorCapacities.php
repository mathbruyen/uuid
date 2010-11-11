<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class representing generators capacities
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
 * Class representing capacities of a UUID generator
 *
 * The capacities defines the possible parameters and tags that this generator handle
 * and that a requirement can use. It also defines the description of the values.
 * 
 * Capacities must define tags that requirements use:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $r = new UUID_UuidRequirements();
 * 
 * $r->addTag('foo');
 * $gc->fulfillRequirements($r);// false, 'foo' tag required in capacities
 * 
 * $gc->addTag('foo');// requirements requiring tag 'foo' are now allowed
 * $gc->fulfillRequirements($r1);// true, 'foo' tag handled
 * </code>
 * Requirements may not use all tags:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $r = new UUID_UuidRequirements();
 * 
 * $gc->addTag('foo');// requirements requiring tag 'foo' are now allowed
 * $gc->fulfillRequirements($r);// true, no tag required
 * </code>
 * 
 * All parameters used in requirements must be defined in capcities:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $r = new UUID_UuidRequirements();
 * 
 * $r->addParameter('bla', 10);
 * $gc->fulfillRequirements($r);// false, 'bla' parameter not allowed
 * 
 * $pd = new UUID_IntegerParameterDescription();
 * $gc->addParameter('bla', $pd, false);// requirements can now use parameter 'bla'
 * $gc->fulfillRequirements($r);// true, parameter 'bla' provided
 * </code>
 * Unrequired parameters are not mandatory:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $r = new UUID_UuidRequirements();
 * 
 * $pd = new UUID_IntegerParameterDescription();
 * $gc->addParameter('bla', $pd, false);// requirements can now use parameter 'bla'
 * 
 * $gc->fulfillRequirements($r);// true, parameter 'bla' not required
 * </code>
 * However all required parameters must be used in requirements:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $r = new UUID_UuidRequirements();
 * 
 * $pd = new UUID_IntegerParameterDescription();
 * $gc->addParameter('bla', $pd, true);// requirements must now use parameter 'bla'
 * 
 * $gc->fulfillRequirements($r);// false, 'bla' parameter required
 * 
 * $r->addParameter('bla', 10);
 * $gc->fulfillRequirements($r);// true, parameter 'bla' provided
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
class UUID_GeneratorCapacities
{
    
    /**
     * The names of the required parameters
     *
     * @var array
     * @access private
     */
    private $_requiredParameterNames = array();
    
    /**
     * All parameter names with their descriptions
     * 
     * Keys of the array are parameter names, pointing to their description that
     * can be used to check the validity of a value.
     *
     * @var array
     * @access private
     */
    private $_allParameters = array();
    
    /**
     * The set of tags that the generator fulfill
     *
     * @var array
     * @access private
     */
    private $_tags = array();
    
    /**
     * Constructor of the capacities
     *
     * Does nothing.
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function __construct()
    {
    }
    
    /**
     * Allows a new parameter and gives its description
     * 
     * The new parameter is retained and requirements using this parameter name will
     * be allowed. The description is retained too to check the values used in
     * requirements. If required is set to true, requirements that do not define this
     * parameter will be rejected.
     * 
     * @param string                    $name        the name of the parameter
     * @param UUID_ParameterDescription $description the description of allowed
     *                                                  values
     * @param boolean                   $required    whether the parameter is
     *                                                  required or not
     * 
     * @return UUID_GeneratorCapacities the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addParameter($name, $description, $required = true)
    {
        $name = "{$name}";
        if (($required) && (!in_array($name, $this->_requiredParameterNames))) {
            $this->_requiredParameterNames[] = $name;
        }
        $this->_allParameters[$name] = $description;
        return $this;
    }
    
    /**
     * Allows a new tag
     *
     * Requirements requiring this tag will be then allowed.
     * 
     * @param string $tag the tag allowed
     * 
     * @return UUID_GeneratorCapacities the current object, for chaining purpose
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addTag($tag)
    {
        $tag = "{$tag}";
        if (!in_array($tag, $this->_tags)) {
            $this->_tags[] = $tag;
        }
        return $this;
    }
    
    /**
     * Returns all tags accepted
     *
     * All tags accepted are returned in an array.
     * 
     * @return array all the accepted tags
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getTags()
    {
        return $this->_tags;
    }
    
    /**
     * Checks if these capacities fulfills the requirements
     *
     * All parameters and tags used in the requirements must be declared. All values
     * must be in accordance with parameters descritption. All required parameters
     * for these capacities must be used in the requirements.
     * 
     * @param array $requirements the requirements to test
     * 
     * @return boolean <code>true</code> the capacities fulfill the requirements
     *                  <code>false</code> otherwise
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function fulfillRequirements($requirements)
    {
        // All required parameters are here
        $intersect = array_intersect(
            $this->_requiredParameterNames,
            array_keys($requirements->getParameters())
        );
        if (count($this->_requiredParameterNames) !== count($intersect)) {
            return false;
        }
        
        // All parameters used are defined
        $intersect = array_intersect(
            array_keys($this->_allParameters),
            array_keys($requirements->getParameters())
        );
        if (count($requirements->getParameters()) !== count($intersect)) {
            return false;
        }
        
        // All values acceptable
        foreach ($requirements->getParameters() as $name => $value) {
            if (!$this->_allParameters[$name]->check($value)) {
                return false;
            }
        }
        
        // All tags are present
        $intersect = array_intersect(
            $this->_tags,
            $requirements->getTags()
        );
        if (count($requirements->getTags()) !== count($intersect)) {
            return false;
        }
        
        // All test successfull
        return true;
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