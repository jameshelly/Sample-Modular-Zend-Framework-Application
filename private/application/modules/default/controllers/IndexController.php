<?php

//namespace Application\Module\Controller;

use /* Opentag, */ \Doctrine\ORM\EntityManager,
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

    /**
     * @var \App\Service\RandomQuote
     * @InjectService RandomQuote
     */
    protected $_randomQuote = null;

    public function init() {
        $this->view->headTitle('title');
    }

    /**
     * This action handles
     *    - Default page
     */
    public function indexAction() {
        $this->view->message = "<p>Sample Zend Modular Application</p>";
        $this->view->message .= "<p><a href='" . $this->view->url(array('module' => 'blog', 'controller' => 'index', 'action' => 'index')) . "'>View the Blog</a></p>";
    }

}