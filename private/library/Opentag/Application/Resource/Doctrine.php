<?php
/*
 * //namespace Opentag\Application\Resource;
 *
 * @since   beta 2.3
 * @version $Revision$
 * @author James A Helly <mrhelly@gmail.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @subpackage Doctrine Adapter
 * @package Opentag
 * @category Opentag
 *
 * @see Zend_Application_Resource_ResourceAbstract
 * require_once 'Zend/Application/Resource/ResourceAbstract.php';
 *
 */
use Doctrine\DBAL\DriverManager,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    Doctrine\Common\EventManager,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\DBAL\Logging\DebugStack as DoctrineDebugStack,
    Doctrine\ORM\Mapping\Driver\DriverChain as DoctrineDriverChain,
    Opentag\Auth\Adapter\Doctrine as AuthAdapterDoctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugDoctrine,
    Opentag\Service\Doctrine as DoctrineService,
    Zend_Application_Resource_ResourceAbstract as ResourceAbstract
//    Gedmo\Tree\TreeListener,
//    Gedmo\Loggable\LoggableListener,
//    Gedmo\Sluggable\SluggableListener,
//    Gedmo\Translatable\TranslationListener,
//    Gedmo\Timestampable\TimestampableListener
    ;

/**
 * Description of Doctrine
 *
 * @author mrhelly
 */
class Opentag_Application_Resource_Doctrine extends ResourceAbstract {


    /**
     * @var  \Opentag\Service\Doctrine
     */
    protected $d2;

    /**
     * @var \Doctrine\Common\EventManager
     */
    protected $iEvtMgr;

    /**
     * @var \Doctrine\Common\EntityManager
     */
    protected $iXmlEntMgr;

    /**
     * @var \Doctrine\Common\EntityManager
     */
    protected $iDocEntMgr;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $iPdoEntMgr;

    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $iConn;

    /**
     * @var Zend_Log
     */
    private $log;

    /**
     * @var \Doctrine\ORM\Configuration
     */
    private $cfg;

    /**
     * Constructor.
     *
     * @param array $config Doctrine Container configuration
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } else if ($options instanceof Zend_Config) {
            $this->setOptions($options->toArray());
        }
//        #Initialise default state for query.
//         #Get logger
        $this->log = $this->getBootstrap()->getContainer()->get('logger');
        $this->log->info(get_class($this) . '::init');
//        #Use apc for queryCache & configCache {session,default,autheduser,authpipe}
//        $this->cfg = new \Doctrine\ORM\Configuration;
//        $this->evtm = $this->getEntityManager();
//        $this->entm = $this->getEntityManager();
   }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function init() {
        return new DoctrineService($this->getOptions());
    }

}
