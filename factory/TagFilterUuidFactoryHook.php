<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of UUID factory hook that filters generators based on tags they have
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

// Parent interface
require_once realpath(__DIR__ . '/UuidFactoryHook.php');

/**
 * Class representing UUID factory hook that filters generators based on tags they
 * have
 *
 * This hook classifies generators by the tags they have and returns only ids of
 * generators that have all the tags required.
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
class UUID_TagFilterUuidFactoryHook implements UUID_UuidFactoryHook
{
    
    /**
     * An array containing for each tag the set of ids of generators that have the
     * corresponding tag.
     *
     * @var array
     * @access private
     */
    private $_idsByTag = array();
    
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
     * Informs the hook that a new generator is available
     * 
     * All generator in the factory are also passed to this method when the hook is
     * added to the factory.
     * 
     * @param int                $id        the identifier that has been given to the
     *                                          generator
     * @param UUID_UuidGenerator $generator the generator beeing added
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function addGenerator($id, $generator)
    {
        foreach ($generator->getCapacities()->getTags() as $tag) {
            if (!array_key_exists($tag, $this->_idsByTag)) {
                $this->_idsByTag[$tag] = array();
            }
            $this->_idsByTag[$tag][$id] = $id;
        }
    }
    
    /**
     * Informs the hook that a new generator has been removed
     * 
     * @param int $id the identifier of the removed generator
     * 
     * @return void
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function removeGenerator($id)
    {
        foreach ($this->_idsByTag as $tag) {
            if (array_key_exists($id, $tag)) {
                unset($tag[$id]);
            }
        }
    }
    
    /**
     * Restricts the set of possible generators if possible
     * 
     * The methods looks at tags the requirements require and take the intersection
     * of all sets of identifiers corresponding to each of these tags, and to the
     * original set of possible ids.
     * 
     * @param UUID_UuidRequirements $requirements the generator beeing added
     * @param array                 $possibleIds  the ids that are still acceptable
     *                                              up to now
     * 
     * @return array a subarray of the one given in parameters, where clearly
     *                  incompatible generators have been pruned
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function preselectGenerators($requirements, $possibleIds)
    {
        $tags = $requirements->getTags();
        if (count($tags) === 0) {
            return $possibleIds;// Cannot filter more
        }
        $possibleSets = array($possibleIds);
        foreach ($tags as $tag) {
            if (array_key_exists($tag, $this->_idsByTag)) {
                $possibleSets[] = $this->_idsByTag[$tag];
            } else {
                return array();// No generator can handle requirements
            }
        }
        return call_user_func_array('array_intersect', $possibleSets);
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