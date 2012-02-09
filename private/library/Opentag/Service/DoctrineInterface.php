<?php
namespace Opentag\Service;

use Doctrine\DBAL\Connection,
    Doctrine\DBAL\Configuration,
    Doctrine\OXM\Configuration,
    Doctrine\ORM\Configuration,
    Doctrine\OXM\Events,
    Doctrine\ORM\Events,
    Doctrine\DBAL\DriverManager,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Doctrine\Common\EventManager;
/**
 * Description of Doctrine
 *
 * @author mrhelly
 */
class DoctrineInterface {

    protected $evtm;
    protected $entm;
    protected $conn;
    protected $log;
    protected $cfg;

    public function getConnection();
    public function getConfiguration($type = "orm-default");
    public function getDriverManager();
    public function getEntityManager();
    public function getEventManager();
}