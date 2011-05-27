<?php

namespace Opentag\Loggable\Mapping\Driver;

use Opentag\Mapping\Driver,
    Doctrine\Common\Annotations\AnnotationReader,
    Opentag\Exception\InvalidMappingException;

/**
 * This is an annotation mapping driver for Loggable
 * behavioral extension. Used for extraction of extended
 * metadata from Annotations specificaly for Loggable
 * extension.
 *
 * @author Boussekeyt Jules <jules.boussekeyt@gmail.com>
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Opentag.Loggable.Mapping.Driver
 * @subpackage Annotation
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Annotation implements Driver
{
    /**
     * Annotation to define that this object is loggable
     */
    const LOGGABLE = 'Opentag\\Loggable\\Mapping\\Loggable';

    /**
     * Annotation to define that this property is versioned
     */
    const VERSIONED = 'Opentag\\Loggable\\Mapping\\Versioned';

    /**
     * {@inheritDoc}
     */
    public function validateFullMetadata($meta, array $config)
    {
        if ($config && is_array($meta->identifier) && count($meta->identifier) > 1) {
            throw new InvalidMappingException("Loggable does not support composite identifiers in class - {$meta->name}");
        }
        if (isset($config['versioned']) && !isset($config['loggable'])) {
            throw new InvalidMappingException("Class must be annoted with Loggable annotation in order to track versioned fields in class - {$meta->name}");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        require_once __DIR__ . '/../Annotations.php';
        $reader = new AnnotationReader();
        $reader->setAnnotationNamespaceAlias('Opentag\\Loggable\\Mapping\\', 'Opentag');

        $class = $meta->getReflectionClass();
        // class annotations
        $classAnnotations = $reader->getClassAnnotations($class);
        if (isset($classAnnotations[self::LOGGABLE])) {
            $config['loggable'] = true;
            $annot = $classAnnotations[self::LOGGABLE];
            if ($annot->logEntryClass) {
                if (!class_exists($annot->logEntryClass)) {
                    throw new InvalidMappingException("LogEntry class: {$annot->logEntryClass} does not exist.");
                }
                $config['logEntryClass'] = $annot->logEntryClass;
            }
        }
        // property annotations
        foreach ($class->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }
            // versioned property
            if ($versioned = $reader->getPropertyAnnotation($property, self::VERSIONED)) {
                $field = $property->getName();
                if ($meta->isCollectionValuedAssociation($field)) {
                    throw new InvalidMappingException("Cannot versioned [{$field}] as it is collection in object - {$meta->name}");
                }
                // fields cannot be overrided and throws mapping exception
                $config['versioned'][] = $field;
            }
        }
    }
}