<?php
namespace Opentag;

/*
 * @since   beta 1.0
 * @version $Revision$
 * @author James A Helly <mrhelly@gmail.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @subpackage ControllerInterface CoreAdapter
 * @package Opentag
 * @category Opentag
 *
 */

use \Opentag\Common\Cache\ApcCache,
    \Opentag_Application_Resource_Doctrine;

use Opentag\Exception\DependentComponentNotFoundException;
use Opentag\Exception\IncompatibleComponentVersionException;

interface ControllerInterface {

    /**
     * @var Opentag_Application_Resource_Doctrine
     *
     * $doctrine->evtm;
     * $doctrine->em;
     * $doctrine->conn;
     * $doctrine->log;
     * $doctrine->cfg;
     * $doctrine->getConnection();
     * $doctrine->getEntityManager();
     */
    protected $doctrine;

    //put your code here
}
