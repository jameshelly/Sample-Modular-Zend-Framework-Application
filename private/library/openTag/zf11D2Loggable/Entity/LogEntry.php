<?php

namespace openTag\Loggable\Entity;

/**
 * openTag\Loggable\Entity\LogEntry
 *
 * @Table(
 *     name="ext_log_entries",
 *  indexes={
 *      @index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @index(name="log_user_lookup_idx", columns={"username"})
 *  }
 * )
 * @Entity(repositoryClass="openTag\Loggable\Entity\Repository\LogEntryRepository")
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}