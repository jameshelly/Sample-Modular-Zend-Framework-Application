<?php

namespace Opentag\Exception;

use Opentag\Exception;

/**
 * InvalidMappingException
 * 
 * Triggered when mapping user argument is not
 * valid or incomplete.
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Opentag.Exception
 * @subpackage InvalidMappingException
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class InvalidMappingException 
    extends InvalidArgumentException
    implements Exception
{}