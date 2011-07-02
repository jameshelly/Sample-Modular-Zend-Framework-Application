<?php
/**
 * PagesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_VariablesController
 * @subpackage VariablesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_VariablesController extends Wednesday_Controller_Action {


	
    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	$this->indexAction();
    }

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
        $this->view->variables = $this->em->getRepository('Variables')->findAll();
        $this->view->header = "Variables";
    	$this->view->message = "Administer Variables";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $variable = new Variables;
        $form = new CMSCore_Form_Variables();
        $return = "";
     
    	//to do get publish version
    	$publishVersion='alpha';
    	
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            $variable->setId($this->getUniqid());
            $variable->setName($values['name']);
            $variable->setType($values['type']);
            $variable->setFilter($values['filter']);
           
            
            /* to do get and set current publish version */
            
            //the following needs to get the current working version name.
            //at the time of writing publish had not be written
            $variable->setPublishVersion($publishVersion);
            
            $this->em->persist($variable);
            $this->updateVariableType($variable,$publishVersion);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'variables', 'action' => 'index'))."\">Added ".$values['name']."</a>";
        }
        $this->view->messages = $return;
    }

    /**
     * This action handles
     *    - readAction
     *    - viewAction
     */
    public function readAction() {
    	$this->viewAction();
    }

    /**
     * This action handles
     *    - viewAction
     *    -
     */
    public function viewAction() {
    	$id = $this->getRequest()->getParam('id');
	    $this->view->variables = $this->em->getRepository('Variables')->find($id);
    }

    /**
     * This action handles
     *    - updateAction
     *    - editAction
     */
    public function updateAction() {
    	$this->editAction();
    }

    /**
     * This action handles
     *    - editAction
     *    -
     */
    public function editAction() {
    	$id = $this->getRequest()->getParam('id');
        $variable= $this->em->getRepository('Variables')->find($id);
        $return = "";
        //to do get publish version
    	$publishVersion='alpha';
    	
        if(isset($variable)) {
            $form = new CMSCore_Form_Variables();
           
            $values = array(
                'id' => $variable->getId(),
                'name' => $variable->getName(),
                'type' => $variable->getType(),
                'filter' => $variable->getFilter()
            );
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();

              
                $variable->setName($values['name']);
            	$variable->setType($values['type']);
            	$variable->setFilter($values['filter']);
           
               
                $this->em->persist($variable);
                $this->updateVariableType($variable,$publishVersion);
                
                $this->em->flush();
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'variables', 'action' => 'index')).'">Back</a></p>';
            } else {
                $form->populate($values);
            }
        } else {
        $return = "No Results";
    	}
        $this->view->messages = $return;
        $this->view->form = $form;
    }

    /**
     * This action handles
     *    - deleteAction
     *    -
     */
    public function deleteAction() {
    	$id = $this->getRequest()->getParam('id');
    	$confirmed = $this->getRequest()->getParam('confirm');
        $variable = $this->em->getRepository('Variables')->find($id);
    	if($confirmed) {
            $this->em->remove($variable);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'variables', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'variables', 'action' => 'index'))."'>No</a>";
    	}
    }
    
 
    
   
}