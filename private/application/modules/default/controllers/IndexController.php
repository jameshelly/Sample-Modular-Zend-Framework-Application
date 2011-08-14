<?php
//namespace Application\Module\Controller;

use /*Opentag,*/
    \Doctrine\ORM\EntityManager,
    \Zend_Controller_Action,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;
/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class IndexController extends \Zend_Controller_Action {

     /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    public function init() {
        $this->view->headTitle('title');
    }

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
    	$this->view->message = "index";
    }
}