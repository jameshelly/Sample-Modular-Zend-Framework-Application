<?php
/**
 * RolesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_AccountsController
 * @subpackage AccountsController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_RolesController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
{

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
		$this->view->roles = $this->em->getRepository('Roles')->findAll();
		$this->view->header = "Roles";
    	$this->view->message = "Administer User Roles";
    	
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $role = new Roles;
        $form = new CMSCore_Form_Roles();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            $role->setName($values['name']);
            $role->setDescription($values['description']);
            $role->setBuildOrder(0);
            $parent = $this->em->getRepository('Roles')->find($values['parent_id']);
            if(!is_null($values['parent_id']) && $values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Roles')->find($values['parent_id']);
                    $role->setParent($parent);
            }
            $this->em->persist($role);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'Roles', 'action' => 'index'))."\">Added {$values['name']}</a>";
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
	    $this->view->roles= $this->em->getRepository('Roles')->find($id);
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
        $role = $this->em->getRepository('Roles')->find($id);
        $return = "";
        if(isset($role)) {
            $form = new CMSCore_Form_Roles();
            $parent = $role->getParent();
            $parent_id = (null !== $parent)?$parent->getId():0;
            $values = array('id' => $role->getId(), 'name' => $role->getName(), 'description' => $role->getDescription(),'parent_id'=>$parent_id);
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();  
                $role->setName($values['name']);
                $role->setDescription($values['description']);
                     
                if(!is_null($values['parent_id']) && $values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Roles')->find($values['parent_id']);
                    $role->setParent($parent);
            	}else{
            		$role->setParent($role);		
            	}
                        
                $this->em->persist($role);
                $this->em->flush();
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'Roles', 'action' => 'index')).'">Back</a></p>';
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
        $role = $this->em->getRepository('Roles')->find($id);

    	if($confirmed) {
            $this->em->remove($role);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'Roles', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'Roles', 'action' => 'index'))."'>No</a>";
    	}
    }

}