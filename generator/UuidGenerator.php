<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Definition of the interface for UUID generator
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
 * Inteface of object representation of a Uuid generator
 *
 * Every UUID generator inherits from this interface. It has two methods, one to get
 * the capacities of the generator, describing which parameters are accepted and the
 * accepted values for them. It also defines tags that the generator is said to
 * respect. The other method is used to actually generate one new UUID. Before trying
 * to generate it, requirements should be tested.
 * <code>
 * //$g = make the generator
 * //$r = make some requirements
 * 
 * $gc = $g->getCapacities();
 * if ($gc->fulfillRequirements($r)) {
 *     $uuid = $g->generateUuid($r);
 * } else {
 *     // pick another generator
 * }
 * </code>
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
interface UUID_UuidGenerator
{
    
    /**
     * Generates a new UUID
     *
     * Generates a new UUID respecting the requirements or throw an exception if
     * requirements cannot be achieved.
     * 
     * If requirements comply with generator's capacities (or equivalently if
     * capacities fulfill requirements) should not throw exception. It is still
     * allowed to throw exception, but only if there is a configuration problem. If
     * an exception is thrown after capacities were checked, it thus means a
     * configuration problem and for example the factory is allowed to remove the
     * generator.
     * <code>
     * //$g = make the generator
     * //$r = make some requirements
     * 
     * $gc = $g->getCapacities();
     * if ($gc->fulfillRequirements($r)) {
     *     $uuid = $g->generateUuid($r);// if it fails, it means configuration error
     * }
     * </code>
     * 
     * @param UUID_UuidRequirements $requirements the requirements that are required
     *                                              for the UUID
     * 
     * @return UUID_Uuid a new UUID
     * @throws UUID_Exception an exception is throwed is something goes wrong
     *
     * @access public
     * @since Method available since Release 1.0
     * @see UUID_UuidGenerator::getCapacities()
     */
    public function generateUuid($requirements);

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
    public function getCapacities();
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>