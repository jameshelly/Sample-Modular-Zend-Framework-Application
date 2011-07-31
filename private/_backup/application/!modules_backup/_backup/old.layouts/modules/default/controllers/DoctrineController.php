<?php
/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class DoctrineController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
    	$metaFactory = $this->em->getMetadataFactory();
        $display = "<br/>Try example url which has been set up in routes and pages: /test/page2.html<hr/>";
        $this->log->info(get_class($this).'::indexAction()');
        $this->view->message = $display;//."<pre>".print_r($metaFactory->getAllMetadata(),true)."</pre>";
        $form = new Zend_Form();
        ZendX_JQuery::enableForm($form);
        $form->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form'
        ));
        $mapper = new \Doctrine\Wednesday\Form\FormAbstract();
        $mapper->setEntityManager($this->em);
        
        $classMetadata = $metaFactory->getMetadataFor('Pages');
        $form->addSubForm($mapper->mapEntityToForm($classMetadata), $classMetadata->name);
//        foreach ($metaFactory->getAllMetadata() as $classMetadata) {
//            $form->addSubForm($mapper->mapEntityToForm($classMetadata), $classMetadata->name);
//        }
        $this->view->message .= $form;
    }
}