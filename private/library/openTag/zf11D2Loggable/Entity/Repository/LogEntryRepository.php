<?php

namespace openTag\Loggable\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use openTag\Loggable\LoggableListener;

/**
 * The LogEntryRepository has some useful functions
 * to interact with log entries.
 *
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag\Loggable\Entity\Repository
 * @subpackage LogEntryRepository
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class LogEntryRepository extends EntityRepository
{
    /**
     * Currently used loggable listener
     *
     * @var LoggableListener
     */
    private $listener;

    /**
     * Loads all log entries for the
     * given $entity
     *
     * @param object $entity
     * @return array
     */
    public function getLogEntries($entity)
    {
        $q = $this->getLogEntriesQuery($entity);
        return $q->getResult();
    }

    /**
     * Get the query for loading of log entries
     *
     * @param object $entity
     * @return Query
     */
    public function getLogEntriesQuery($entity)
    {
        $objectClass = get_class($entity);
        $objectMeta = $this->_em->getClassMetadata($objectClass);
        $meta = $this->getClassMetadata();
        $dql = "SELECT log FROM {$meta->name} log";
        $dql .= " WHERE log.objectId = :objectId";
        $dql .= " AND log.objectClass = :objectClass";
        $dql .= " ORDER BY log.version DESC";

        $identifierField = $objectMeta->getSingleIdentifierFieldName();
        $objectId = $objectMeta->getReflectionProperty($identifierField)->getValue($entity);
        $q = $this->_em->createQuery($dql);
        $q->setParameters(compact('objectId', 'objectClass', 'order'));
        return $q;
    }

    /**
     * Reverts given $entity to $revision by
     * restoring all fields from that $revision.
     * After this operation you will need to
     * persist and flush the $entity.
     *
     * @param object $entity
     * @param integer $version
     * @throws \openTag\Exception\UnexpectedValueException
     * @return void
     */
    public function revert($entity, $version = 1)
    {
        $objectClass = get_class($entity);
        $objectMeta = $this->_em->getClassMetadata($objectClass);
        $meta = $this->getClassMetadata();
        $dql = "SELECT log FROM {$meta->name} log";
        $dql .= " WHERE log.objectId = :objectId";
        $dql .= " AND log.objectClass = :objectClass";
        $dql .= " AND log.version <= :version";
        $dql .= " ORDER BY log.version ASC";

        $identifierField = $objectMeta->getSingleIdentifierFieldName();
        $objectId = $objectMeta->getReflectionProperty($identifierField)->getValue($entity);
        $q = $this->_em->createQuery($dql);
        $q->setParameters(compact('objectId', 'objectClass', 'version'));
        $logs = $q->getResult();

        if ($logs) {
            $config = $this->getLoggableListener()->getConfiguration($this->_em, $objectMeta->name);
            $fields = $config['versioned'];
            $filled = false;
            while (($log = array_pop($logs)) && !$filled) {
                if ($data = $log->getData()) {
                    foreach ($data as $field => $value) {
                        if (in_array($field, $fields)) {
                            if ($objectMeta->isSingleValuedAssociation($field)) {
                                $mapping = $objectMeta->getAssociationMapping($field);
                                $value = $value ? $this->_em->getReference($mapping['targetEntity'], $value) : null;
                            }
                            $objectMeta->getReflectionProperty($field)->setValue($entity, $value);
                            unset($fields[array_search($field, $fields)]);
                        }
                    }
                }
                $filled = count($fields) === 0;
            }
            if (count($fields)) {
                throw new \openTag\Exception\UnexpectedValueException('Cound not fully revert the entity to version: '.$version);
            }
        } else {
            throw new \openTag\Exception\UnexpectedValueException('Count not find any log entries under version: '.$version);
        }
    }

    /**
     * Get the currently used LoggableListener
     *
     * @throws \openTag\Exception\RuntimeException - if listener is not found
     * @return LoggableListener
     */
    private function getLoggableListener()
    {
        if (is_null($this->listener)) {
            foreach ($this->_em->getEventManager()->getListeners() as $event => $listeners) {
                foreach ($listeners as $hash => $listener) {
                    if ($listener instanceof LoggableListener) {
                        $this->listener = $listener;
                        break;
                    }
                }
                if ($this->listener) {
                    break;
                }
            }

            if (is_null($this->listener)) {
                throw new \openTag\Exception\RuntimeException('The loggable listener could not be found');
            }
        }
        return $this->listener;
    }
}