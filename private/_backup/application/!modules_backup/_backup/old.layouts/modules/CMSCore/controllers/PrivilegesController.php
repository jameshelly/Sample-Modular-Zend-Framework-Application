<?php
/**
 * RoutesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_PriviledgesController
 * @subpackage PriviledgesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_PrivilegesController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
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
		$this->view->privileges = $this->em->getRepository('Privileges')->findAll();
		$this->view->header = "Privileges";
    	$this->view->message = "Administer Privileges";
    	
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $privileges = new Privileges;
        $form = new CMSCore_Form_Privileges();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            //$privileges->setPrivilege($values['privilege']);
            
            $newrole = $this->em->getRepository('Roles')->find($values['role_id']);
            $privileges->setRole($newrole);
            
            $privileges->setAllowed($values['allowed']);
            if(!empty($values['aclresource_id']) && $values['aclresource_id']>0){
            	$newresource = $this->em->getRepository('Aclresources')->find($values['aclresource_id']);
            	$privileges->setResource($newresource);
            } 
//print_r($values);
//exit();
            if(!empty($values['privilegetypes_id']) && $values['privilegetypes_id']>0){
            	$newprivilege = $this->em->getRepository('Privilegetypes')->find($values['privilegetypes_id']);
            	$privileges->setPrivilegetype($newprivilege );
            } 
            
            $this->em->persist($privileges);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'privileges', 'action' => 'index'))."\">Added {$newprivilege->getName()}</a>";
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
	    $this->view->privileges = $this->em->getRepository('Privileges')->find($id);
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
        $privilege = $this->em->getRepository('Privileges')->find($id);
        $return = "";
        if(isset($privilege)) {
            $form =  new CMSCore_Form_Privileges();
            $role=$privilege->getRole();
            $resource=$privilege->getResource();
            $privilegeType=$privilege->getPrivilegeType();
          
            $values = array('id' => $privilege->getId(), 'privilegetypes_id' => $privilegeType->getId(), 'allowed' => $privilege->getAllowed(),'aclresource_id'=>$resource->getId(),'role_id'=>$role->getId());
            if($form->isValid($_POST)) {
                
                $form->populate($_POST);
                $values = $form->getValues();
                
                $privilege->setAllowed($values['allowed']);
                if(!empty($values['aclresource_id']) && $values['aclresource_id']>0){
            	    $newresource = $this->em->getRepository('Aclresources')->find($values['aclresource_id']);
            	    $privilege->setResource($newresource);
                } 
                if(!empty($values['privilegetypes_id']) && $values['privilegetypes_id']>0){
            		$newprivilege = $this->em->getRepository('Privilegetypes')->find($values['privilegetypes_id']);
            		$privilege->setPrivilegetype($newprivilege );
            	}    

            	$newrole = $this->em->getRepository('Roles')->find($values['role_id']);
            	$privilege->setRole($newrole);
            	
                $this->em->persist($privilege);
                $this->em->flush();
                
                
                
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'privileges', 'action' => 'index')).'">Back</a></p>';
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
        $privilege = $this->em->getRepository('Privilege')->find($id);

    	if($confirmed) {
            $this->em->remove($privilege);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'privileges', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'privileges', 'action' => 'index'))."'>No</a>";
    	}
    }

}