<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Interface defining UUID factory hooks
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
 * Interface for UUID factory hook
 *
 * Hooks are intended to add modularity to the factory. In particular they help the
 * factory preselecting generators. They are not aimed at only return valid
 * generators but more to remove the ones that will obvioulsy not fulfill
 * requirements. More specifically, it is of NO USE to have a hook calling
 * fulfillRequirements method.
 * 
 * Other methods are provided to help the heek keeping an up to date set of
 * generators. Even if the hook is added after UUID generators have been added, these
 * ones will be passed as the hook is added to the factory.
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
interface UUID_UuidFactoryHook
{
    
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
    public function addGenerator($id, $generator);
    
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
    public function removeGenerator($id);
    
    /**
     * Restricts the set of possible generators if possible
     * 
     * The methods receives an array of generator identifiers and can restrict it if
     * some clearly not fulfill requirements. This method MUST not add new
     * identifiers. This method MUST not call fulfillRequirements method, that will
     * be called afterwards. It is intended to prune clearly not compatible
     * generators, with heuristics faster than fulfillRequirements. If the method
     * discovers that no generator fulfill the requirements it can simply return an
     * empty array, but no exception are allowed.
     * 
     * @param UUID_UuidRequirements $generator   the generator beeing added
     * @param array                 $possibleIds the ids that are still acceptable up
     *                                              to now
     * 
     * @return array a subarray of the one given in parameters, where clearly
     *                  incompatible generators have been pruned
     *
     * @access public
     * @since Method available since Release 1.0
     */
    public function preselectGenerators($requirements, $possibleIds);
    
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>