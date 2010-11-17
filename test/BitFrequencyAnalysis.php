<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Script to manually check distribution properties of bits
 * 
 * This script helps to manually checks basic distribution of bits in UUID generated.
 * You can define the requirements you want to use, that may depend on the
 * iteration number, and a large number of UUIDs will be generated. They are then put
 * into an array that will be printed at the end. It represents the expected value of
 * each bit.
 * 
 * For example for a random RFC4122 UUID we expect all frequencies to be around 50%
 * except for bits defining the variant and layout that must have frequency 0 or 1
 * depending on their position.
 * 
 * This scripts doesn't prove that anything works, but it can be used to visually
 * check that layout and version are correct, as well as other bits do not stay
 * constantly in one position. However, in some cases this behavior of stable bits is
 * completely normal, as in time based UUIDs.
 * 
 * Tests summary:
 * DATE       | Description
 * 2010-11-17 | Random RFC4122 UUIDs
 * 2010-11-17 | Sha-1 name based RFC4122 UUID with different names
 * 2010-11-17 | Sha-1 name based RFC4122 UUID with different namespaces
 * 2010-11-17 | Md5 name based RFC4122 UUID with different names
 * 2010-11-17 | Md5 name based RFC4122 UUID with different namespaces
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

// Load package
require_once realpath(__DIR__ . '/../uuid.php');

/**
* Creates requirements instance used to the analysis
* 
* Requirements can depend on the iteration index. This method will be called before
* each generation is made. There are some examples of what can be done commented.
*
* @param int $i the index of the iteration
* 
* @return UUID_UuidRequirements the requirements used in this iteration
*/
function defineRequirements($i)
{
    $r = new UUID_UuidRequirements();
    
    //UUID_RequirementsLibrary::requestRfc4122($r, 4);
    
    //UUID_RequirementsLibrary::requestRfc4122($r);
    //UUID_RequirementsLibrary::requestRfc4122($r, 3);
    //UUID_RequirementsLibrary::requestRfc4122($r, 5);
    //UUID_RequirementsLibrary::requestName($r, 'name' . $i, 'namespace');
    //UUID_RequirementsLibrary::requestName($r, 'name', 'namespace' . $i);
    
    return $r;
}

try {
    $frequency = array();
    $repetitions = 1000;
    for ($i = 0; $i < $repetitions; $i++) {
        $uuid = UUID_UuidFactory::get()->generate(defineRequirements($i));
        $j = 0;
        foreach (array_reverse(str_split($uuid->toInt()->toBits())) as $char) {
            if (!array_key_exists($j, $frequency)) {
                $frequency[$j] = 0;
            }
            $frequency[$j] += intval($char);
            $j++;
        }
    }
    for ($i = 0; $i < count($frequency); $i++) {
        $frequency[$i] = $frequency[$i]/$repetitions;
    }
    var_export($frequency);
} catch (UUID_Exception $e) {
    echo $e->getMessage();
}
echo "\n";

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>