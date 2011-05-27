<?php

namespace openTag\Loggable\Mapping\Event\Adapter;

use openTag\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use openTag\Loggable\Mapping\Event\LoggableAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for Loggable behavior
 *
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag\Loggable\Mapping\Event\Adapter
 * @subpackage ORM
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements LoggableAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getDefaultLogEntryClass()
    {
        return 'openTag\\Loggable\\Entity\\LogEntry';
    }

    /**
     * {@inheritDoc}
     */
    public function getNewVersion($meta, $object)
    {
        $em = $this->getObjectManager();
        $objectMeta = $em->getClassMetadata(get_class($object));
        $identifierField = $this->getSingleIdentifierFieldName($objectMeta);
        $objectId = $objectMeta->getReflectionProperty($identifierField)->getValue($object);

        $dql = "SELECT MAX(log.version) FROM {$meta->name} log";
        $dql .= " WHERE log.objectId = :objectId";
        $dql .= " AND log.objectClass = :objectClass";

        $q = $em->createQuery($dql);
        $q->setParameters(array(
            'objectId' => $objectId,
            'objectClass' => $objectMeta->name
        ));
        return $q->getSingleScalarResult() + 1;
    }
}