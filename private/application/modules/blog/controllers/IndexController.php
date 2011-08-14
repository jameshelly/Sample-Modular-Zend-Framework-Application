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
    const ENTITY_NAME = "Application\Entities\Articles";

    public function init()
    {
    	#Get Doctrine Entity Manager
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->em = $bootstrap->getContainer()->get('entity.manager');
        //$this->view->placeholder('doctrine')->exchangeArray(array('em'=>$this->em));
        $this->view->partialLoop()->setObjectKey('entity');
        $this->view->headTitle('blog');
    }

    public function indexAction()
    {
        // action body
        $this->view->title = "Blog Title";
        $this->view->message = "Blog Message";
        $this->view->content = "<p>Featured Blog Content</p>";
        $this->view->articles = $this->em->getRepository(self::ENTITY_NAME)->findAll();
    }

}





