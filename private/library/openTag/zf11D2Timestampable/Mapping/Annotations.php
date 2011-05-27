<?php

namespace Opentag\Timestampable\Mapping;

use Doctrine\Common\Annotations\Annotation;

/**
 * These are Timestampable extension annotations which should be used
 * for date field updates on creation, modification or even on property change
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Opentag.Timestampable.Mapping
 * @subpackage Annotations
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

final class Timestampable extends Annotation {
    public $on = 'update';
    public $field;
    public $value;
}