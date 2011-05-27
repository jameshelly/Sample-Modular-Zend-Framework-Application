<?php

namespace openTag\Timestampable\Mapping\Event;

use openTag\Mapping\Event\AdapterInterface;

/**
 * Doctrine event adapter interface
 * for Timestampable behavior
 *
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag\Timestampable\Mapping\Event
 * @subpackage TimestampableAdapter
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface TimestampableAdapter extends AdapterInterface
{
    /**
     * Get the date value
     *
     * @param ClassMetadata $meta
     * @param string $field
     * @return mixed
     */
    function getDateValue($meta, $field);
}