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
        //$this->_loginForm = new Application_Form_Login();
        //$this->view->form = $this->_loginForm;
        // get auth service from bootstrap
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_auth = $bootstrap->getResource('auth');
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