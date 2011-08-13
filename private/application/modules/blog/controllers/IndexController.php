<?php
//namespace Application\Module\Controller;

use Doctrine\ORM\EntityManager,
    \Application\Forms,
    \Application\Entities,
    \Zend_Controller_Action,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;

class Blog_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $this->view->title = "Blog";
        $this->view->message = "Blog Message";
        $this->view->content = "Blog Content";
    }

}





