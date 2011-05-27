<?php

namespace openTag;

/**
 * Common package exception interface to allow
 * users of caching only this package specific
 * exceptions thrown
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag
 * @subpackage Exception
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Exception
{
    /**
     * Following best practices for PHP5.3 package exceptions.
     * All exceptions thrown in this package will have to implement this interface
     * 
     * @link http://wiki.php.net/pear/rfc/pear2_exception_policy
     */
}