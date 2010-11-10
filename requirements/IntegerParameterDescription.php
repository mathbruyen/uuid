<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class checking integer parameters
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

// Load exception
require_once realpath(__DIR__) . '/../util/Exception.php';

// Load the interface
require_once realpath(__DIR__) . '/../requirements/ParameterDescription.php';

/**
 * Class representing description of an integer parameter
 *
 * It can be used to check that the integer is greater than a minimum (included)
 * <code>
 * $pd = new UUID_IntegerParameterDescription();
 * $pd->setMinValue(4);
 * 
 * $pd->check(5);// true
 * $pd->check(3);// false
 * </code>
 * It can also be used to check that the integer is lower than a maximum (included)
 * <code>
 * $pd = new UUID_IntegerParameterDescription();
 * $pd->setMaxValue(4);
 * 
 * $pd->check(3);// true
 * $pd->check(5);// false
 * </code>
 * Another use is to set both maximum and minimum (included)
 * <code>
 * $pd = new UUID_IntegerParameterDescription();
 * $pd->setMinValue(4);
 * $pd->setMaxValue(10);
 * 
 * $pd->check(3);// false
 * $pd->check(5);// true
 * $pd->check(12);// false
 * </code>
 * Again another possibility is to use an array of possible values:
 * <code>
 * $pd = new UUID_IntegerParameterDescription();
 * $pd->setValues(array(1, 2, 4));
 * 
 * $pd->check(2);// true
 * $pd->check(3);// false
 * </code>
 * The last possibility is to use both paradigm, setting min and/or max and a set of
 * possible values. In all cases the type is checked to only have integers. 
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
class UUID_IntegerParameterDescription implements UUID_ParameterDescription
{
    
    /**
     * The minimum value to allow or null if no limitation
     *
     * @var int
     * @access private
     */
    private $_min = null;
    
    /**
     * The maximum value to allow or null if no limitation
     *
     * @var int
     * @access private
     */
    private $_max = null;
    
    /**
     * The set of values to allow null if any available
     *
     * @var array
     * @access private
     */
    private $_values = null;
    
    /**
     * Constructor of the parameter description
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
     * Sets the minimum value to allow
     * 
     * The value of the minimum is set and value lower than that will be then
     * rejected in check().
     * <code>
     * $pd = new UUID_IntegerParameterDescription();
     * 
     * $pd->check(3);// true
     * 
     * $pd->setMinValue(4);
     * 
     * $pd->check(3);// false
     * </code>
     * 
     * @param int $min the minimum value accepted
     * 
     * @return void
     * @throw UUID_Exception if the provided value is not an int or is inconsistent
     *                          with maximum
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function setMinValue($min)
    {
        if (!is_int($min)) {
            throw new UUID_Exception('Min must be an integer');
        }
        if (($this->_max !== null) && ($min > $this->_max)) {
            throw new UUID_Exception('Value given for min is greater than max');
        }
        $this->_min = $min;
    }
    
    /**
     * Sets the minimum value to allow
     * 
     * The value of the maximum is set and value greater than that will be then
     * rejected in check().
     * <code>
     * $pd = new UUID_IntegerParameterDescription();
     * 
     * $pd->check(5);// true
     * 
     * $pd->setMaxValue(4);
     * 
     * $pd->check(5);// false
     * </code>
     * 
     * @param int $max the maximum value accepted
     * 
     * @return void
     * @throw UUID_Exception if the provided value is not an int or is inconsistent
     *                          with minimum
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function setMaxValue($max)
    {
        if (!is_int($max)) {
            throw new UUID_Exception('Max must be an integer');
        }
        if (($this->_min !== null) && ($this->_min > $max)) {
            throw new UUID_Exception('Value given for max is lower than min');
        }
        $this->_max = $max;
    }
    
    /**
     * Sets the set of acceptable values
     * 
     * The set of acceptable values is defined and value out of this set will be then
     * rejected in check().
     * <code>
     * $pd = new UUID_IntegerParameterDescription();
     * 
     * $pd->check(5);// true
     * 
     * $pd->setValues(array(3, 6));
     * 
     * $pd->check(5);// false
     * </code>
     * 
     * @param array $values an array of integers
     * 
     * @return void
     * @throw UUID_Exception if values is not an array or if it contains something
     *                          else than an int
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function setValues($values)
    {
        if (!is_array($values)) {
            throw new UUID_Exception('Values must be an array of integer');
        }
        foreach ($values as $value) {
            if (!is_int($value)) {
                throw new UUID_Exception('Values must be an array of integer');
            }
        }
        $this->_values = $values;
    }
    
    /**
     * Informs if the value is acceptable or not for this description
     * 
     * The value is acceptable if all of:
     * - value is an int
     * - if minimum is set, value is greater or equal than this minimum
     * - if maximum is set, value is greater or equal than this maximum
     * - if possible values are specified, value is in this set
     * are valid.
     * 
     * @param mixed $value the value to test
     * 
     * @return boolean <code>true</code> if the value corresponds to the description
     *                  <code>false</code> otherwise
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function check($value)
    {
        if (!is_int($value)) {
            return false;
        }
        if (($this->_min !== null) && ($value < $this->_min)) {
            return false;
        }
        if (($this->_max !== null) && ($value > $this->_max)) {
            return false;
        }
        if (($this->_values !== null) && (!in_array($value, $this->_values))) {
            return false;
        }
        return true;
    }
    
    /**
     * Returns a description of the requirements, understandable by a human being
     * 
     * @return string a human readable help to explain how to comply
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function getHumanReadableHelp()
    {
        // @codeCoverageIgnoreStart
        $info = array();
        if ($this->_min !== null) {
            $info[] = "greater or equal than {$this->_min}";
        }
        if ($this->_max !== null) {
            $info[] = "lower or equal than {$this->_max}";
        }
        if ($this->_values !== null) {
            $info[] = 'in { ' . implode(', ', $this->_values) . ' }';
        }
        return 'The value must be an integer ' . implode(', ', $info) . '.';
        // @codeCoverageIgnoreEnd
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