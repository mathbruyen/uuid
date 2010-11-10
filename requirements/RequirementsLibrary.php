<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the class defining tags and parameters in a consistent way
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

// Load integer parameters
require_once realpath(__DIR__) . '/IntegerParameterDescription.php';

// Load string parameters
require_once realpath(__DIR__) . '/StringParameterDescription.php';

/**
 * Library for defining consistently tags and parameters
 * 
 * This library provides methods modifying uuid requirements objects, or generator
 * capacities objects. Use of these methods is recommended in order to have
 * consistent parameter names and tags in the system.
 * 
 * It can be used to defined parameters related to the raw integer size of the UUID.
 * With an array minimum and maximum values can be defined, as a set listing all
 * possible values and if the parameter is required or not. All of these parameters
 * are optional. An example using all parameters:
 * <code>
 * $gc = new UUID_GeneratorCapacities();
 * $parameters = array(
 *      'min' => 10;
 *      'max' => 100;
 *      'values' => array(1, 2, 3);
 *      'required' => false;// default is true
 * );
 * UUID_RequirementsLibrary::allowSize($gc, $parameters);
 * </code>
 * The requirements can be then defined using the related method (here we request a
 * UUID of raw integer size of 80 bits):
 * <code>
 * $r = new UUID_UuidRequirements();
 * UUID_RequirementsLibrary::requestSize($r, 80);
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
class UUID_RequirementsLibrary
{
    
    /**
     * Parameter name for raw integer size
     *
     * @var string
     */
    const PARAMETER_NAME_SIZE = 'size';
    
    /**
     * Allow size parameter in a UUID generator
     * 
     * A size parameter is then accepted by the generator. It is possible to define
     * restrictions on this size parameter using the array:
     * - the 'min' key corresponds to the minimal size accepted
     * - the 'max' key corresponds to the maximal size accepted
     * - the 'values' key gives an list of all accepted values
     * - the 'required' key indicates if the parameter is optional or not
     * All of these parameter are optional. An example using all is given:
     * <code>
     * $gc = new UUID_GeneratorCapacities();
     * $parameters = array(
     *      'min' => 10;
     *      'max' => 100;
     *      'values' => array(1, 2, 3);
     *      'required' => false;// default is true
     * );
     * UUID_RequirementsLibrary::allowSize($gc, $parameters);
     * </code>
     * Requirements complying with these capacities should be defined by the
     * corresponding method: requestSize.
     * 
     * @param UUID_GeneratorCapacities $capacities the capacities that are to accept
     *                                              the size parameter
     * @param array                    $parameters the parameters to restrict values
     *                                              accepted
     * 
     * @return UUID_GeneratorCapacities the capacities, just for chaining purpose
     *                                  (the original object is modified)
     * @throw UUID_Exception if the given parameters lead to inconsistent parameter
     *                          description
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_RequirementsLibrary::requestSize()
     */
    public static function allowSize($capacities, $parameters = array())
    {
        static $minKey = 'min';
        static $maxKey = 'max';
        static $valuesKey = 'values';
        static $requiredKey = 'required';
        
        $pd = new UUID_IntegerParameterDescription();
        // Minimum (set in all cases greater than 0)
        $min = 0;
        if (array_key_exists($minKey, $parameters)) {
            $temp = $parameters[$minKey];
            if ($temp > 0) {
                $min = $temp;
            }
        }
        $pd->setMinValue($min);
        
        // Maximum
        if (array_key_exists($maxKey, $parameters)) {
            $pd->setMaxValue($parameters[$maxKey]);
        }
        
        // Possible values
        if (array_key_exists($valuesKey, $parameters)) {
            $pd->setValues($parameters[$valuesKey]);
        }
        
        $required = true;
        if ((array_key_exists($requiredKey, $parameters))
                && ($parameters[$requiredKey] === false)) {
            $required = false;
        }
        
        $capacities->addParameter(self::PARAMETER_NAME_SIZE, $pd, $required);
        return $capacities;
    }
    
    /**
     * Requires that UUID accepts a size parameter
     * 
     * Requirements passed to this method will then require that generated UUID has
     * a raw integer representation of the given bit number. In the following
     * example the UUID will have a raw integer representation of size 80 bits:
     * <code>
     * $r = new UUID_UuidRequirements();
     * UUID_RequirementsLibrary::requestSize($r, 80);
     * </code>
     * Generators that accept these requirements should have their capacities defined
     * by the corresponding methods: allowSize.
     * 
     * @param UUID_UuidRequirements $requirements the requirements
     * 
     * @return void
     * @throw UUID_Exception if the provided value is not an int or is inconsistent
     *                          with maximum
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_RequirementsLibrary::requestSize()
     */
    public static function requestSize($requirements, $size)
    {
        $requirements->addParameter(self::PARAMETER_NAME_SIZE, $size);
        return $requirements;
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