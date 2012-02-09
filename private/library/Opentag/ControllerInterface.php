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

use Opentag\Common\Cache\ApcCache,
    Opentag_Application_Resource_Doctrine as DoctrineResource,
    Opentag\Service\Doctrine as DoctrineService,
    Opentag\Exception\DependentComponentNotFoundException,
    Opentag\Exception\IncompatibleComponentVersionException,
    Doctrine\Common\PropertyChangedListener as PropertyListener;

interface ControllerInterface extends PropertyChangedListener {

    public function init();

    public function indexAction();

}
