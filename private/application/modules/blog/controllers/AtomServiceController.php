<?php

//namespace Application\Module\Controller;

use Doctrine\ORM\EntityManager,
    Opentag\ControllerInterface as ControllerI,
//    Application\Forms,
//    Application\Entities,
    Opentag\Service\Doctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugPlugin,
    Zend_Controller_Action as ControllerAction,
    Opentag\ActionController,
    Zend\Di\Configuration,
    Zend\Di\ServiceLocation,
    \Zend_Auth_Result;

class Blog_AtomServiceController extends ControllerAction /*ActionController*/ {


    public function indexAction() {

//        $this->_cfg;
//        $this->_log;
//        $this->_entm;
//        $this->_evtm;
//        $this->_conn;
//        #Action body

        $this->view->title = "Index Title";
        $this->view->message = "Index Message";
        $this->view->content = "<p>Index Content</p>";
        $this->view->articles = (isset($this->entm)===false)?"none found. ":$this->entm->getRepository()->findAll();
    }

}
