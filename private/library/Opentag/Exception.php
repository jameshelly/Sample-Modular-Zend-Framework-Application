<?php

namespace Opentag;

/**
 * Common package exception interface to allow
 * users of caching only this package specific
 * exceptions thrown
 *
 *
 * @since   beta 1.0
 * @version $Revision$
 * @author James A Helly <mrhelly@gmail.com>, Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo
 * @subpackage Exception
 * @link http://www.gediminasm.org
 * @link http://opentag.spyders-lair.com
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
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