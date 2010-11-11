<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Class definition of the factory making UUIDs
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

// Exception class
require_once realpath(__DIR__) . '/../util/Exception.php';

// Generator interface
require_once realpath(__DIR__) . '/../generator/UuidGenerator.php';

// Generator capacities
require_once realpath(__DIR__) . '/../requirements/GeneratorCapacities.php';

// Uuid requirements
require_once realpath(__DIR__) . '/../requirements/UuidRequirements.php';

/**
 * Factory generating UUIDs
 *
 * In this factory it is possible to add instances of generators with priorities.
 * Priorities tell wich generator should be used first in case more than one fulfill
 * the requirements. The one with the highest priority will be tested first. It can
 * be extended by hooks rather than subclasses.
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
class UUID_UuidFactory
{
    
    /**
     * The message given if no generator corresponds to the requirements
     * 
     * It is given as a class constant because is it used in several places in the
     * class.
     *
     * @var string
     */
    const NO_GENERATOR_MESSAGE = 'No generator corresponds to the requirements';
    
    /**
     * The list of generator instances
     *
     * @var array
     * @access private
     */
    private $_instances = array();
    
    /**
     * The list of hooks
     *
     * @var array
     * @access private
     */
    private $_hooks = array();
    
    /**
     * Constructor
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
     * Adds a hook to the factory
     * 
     * All generator in the factory are passed to the new hook as it is added to the
     * factory to allow it having a complete view of generators.
     * 
     * @param UUID_UuidFactoryHook $hook the hook beeing added
     * 
     * @return UUID_UuidFactory the object itself for chaining purposes
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addHook($hook)
    {
        $this->_hooks[] = $hook;
        // Give all previous instances to the hook
        foreach ($this->_instances as $id => $generator) {
            $hook->addGenerator($id, $generator);
        }
        return $this;
    }
    
    /**
     * Adds a generator to the factory
     * 
     * The generator is instantiated and passed to hooks.
     * 
     * @param int    $priority   the priority to give to the generator added
     * @param string $className  the class name of the generator to add
     * @param array  $parameters parameters that will be used to instantiate the
     *                              generator
     * 
     * @return UUID_UuidFactory the object itself for chaining purposes
     * @throws UUID_Exception if there is an error instantiating the object or
     *                          if it does not implements the interface
     * 
     * @access public
     * @since Method available since Release 1.0
     */
    public function addGenerator($priority, $className, $parameters = array())
    {
        $g = $this->_instanciateGenerator($className, $parameters);
        // Insert at the correct place
        $id = $this->_getId($priority);
        $this->_instances[$id] = $g;
        // Informs hooks
        foreach ($this->_hooks as $hook) {
            $hook->addGenerator($id, $g);
        }
        return $this;
    }
    
    /**
     * Generates a new UUID fulfilling the requirements
     * 
     * The factory starts by taking all possible ids and tries to filter them using
     * the hooks. It then iterates in the possible ones in the order of priorities
     * and check that they fulfill the requirements, if not it moves to the next one.
     * If it is the case a uuid is generated and returned (if an exception arises
     * here the generator is removed because it told that it can handler requirements
     * but actually cannot, so there must be a more important problem).
     * 
     * @param UUID_UuidRequirements $requirements the requirements
     * 
     * @return UUID_Uuid the generated UUID
     * @throws UUID_Exception if there is no correct generator
     * 
     * @access public
     * @since Method available since Release 1.0
     */
    public function generate($requirements)
    {
        // Filter possible ids
        $possibleIds = array_keys($this->_instances);
        foreach ($this->_hooks as $hook) {
            $possibleIds = $hook->preselectGenerators($requirements, $possibleIds);
            if (count($possibleIds) === 0) {
                throw new UUID_Exception(self::NO_GENERATOR_MESSAGE);
            }
        }
        // Try to generate a UUID
        sort($possibleIds);
        foreach (array_reverse($possibleIds) as $id) {
            $instance = $this->_instances[$id];
            if ($instance->getCapacities()->fulfillRequirements($requirements)) {
                try {
                    return $instance->generateUuid($requirements);
                } catch (UUID_Exception $e) {
                    // Remove it, it told that it fulfilled requirements but did not
                    $this->_removeGenerator($id);
                }
            }
        }
        throw new UUID_Exception(self::NO_GENERATOR_MESSAGE);
    }
    
    /**
     * Instantiates a generator
     * 
     * The generator is instantiated with the given parameters using the
     * ReflexionClass class.
     * 
     * @param string $className  the class name of the generator instantiated
     * @param array  $parameters the parameters to give to the constructor
     * 
     * @return UUID_UuidGenerator the instantiated UUID generator
     * @throws UUID_Exception if there is an error instantiating the object or
     *                          if it does not implements the interface
     * 
     * @access private
     * @since Method available since Release 1.0
     */
    private function _instanciateGenerator($className, $parameters = array())
    {
        try {
            $r = new ReflectionClass($className);
            $instance = $r->newInstanceArgs($parameters);
        } catch (ReflexionException $e) {
            throw new UUID_Exception('Impossible to instantiate the generator', $e);
        }
        if (!($instance instanceof UUID_UuidGenerator)) {
            throw new UUID_Exception(
                'The class instantiated is not a UUID generator'
            );
        }
        return $instance;
    }
    
    /**
     * Retrieves a free id corresponding to a priority
     * 
     * Checks wether the proposed priority is available, if not it increments it
     * until a free slot is found, and then returns the generated id.
     * 
     * @param int $priority the original priority the id is devised from
     * 
     * @return int the id devised from the priority
     * 
     * @access private
     * @since Method available since Release 1.0
     */
    private function _getId($priority)
    {
        while (array_key_exists($priority, $this->_instances)) {
            $priority++;
        }
        return $priority;
    }
    
    /**
     * Removes a generator
     * 
     * Removes the generator and tells hooks to remove it too
     * 
     * @param int $id the identifier of the corresponding generator
     * 
     * @return void
     * 
     * @access private
     * @since Method available since Release 1.0
     */
    private function _removeGenerator($id)
    {
        // Informs hooks
        foreach ($this->_hooks as $hook) {
            $hook->removeGenerator($id);
        }
        // Remove locally
        unset($this->_instances[$id]);
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