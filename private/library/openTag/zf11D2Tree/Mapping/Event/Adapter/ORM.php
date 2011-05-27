<?php

namespace openTag\Tree\Mapping\Event\Adapter;

use openTag\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use openTag\Tree\Mapping\Event\TreeAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for Tree behavior
 *
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag\Tree\Mapping\Event\Adapter
 * @subpackage ORM
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements TreeAdapter
{
    // Nothing specific yet
}