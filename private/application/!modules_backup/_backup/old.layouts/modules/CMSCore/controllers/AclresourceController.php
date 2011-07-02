<?php
/**
 * AclresourceController - controller class to administer an acl resource
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_AclresourceController
 * @subpackage AclresourceController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_AclresourceController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
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
		$this->view->resources = $this->em->getRepository('Aclresources')->findAll();
		$this->view->header = "Acl Resources";
    	$this->view->message = "Administer Resources temporary listing - to be a tree view";
    	
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $resource = new Aclresources;
        $form = new CMSCore_Form_Aclresources();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            $resource->setType($values['type']);
            $resource->setName($values['name']);
            $resource->setBuildOrder(0);
            $parent = $this->em->getRepository('Aclresources')->find($values['parent_id']);
            if(!is_null($values['parent_id']) && $values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Aclresources')->find($values['parent_id']);
                    $resource->setParent($parent);
            }
           
            $this->em->persist($resource);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'aclresource', 'action' => 'index'))."\">Added {$values['type']} {$values['name']}</a>";
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
	    $this->view->route = $this->em->getRepository('Aclresources')->find($id);
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
        $resource = $this->em->getRepository('Aclresources')->find($id);
        $return = "";
        if(isset($resource)) {
            $form = new CMSCore_Form_Aclresources();
            
            $parent = $resource->getParent();
            $parent_id = (null !== $parent)?$parent->getId():0;
            $values = array('id' => $resource->getId(), 'type' => $resource->getType(), 'name' => $resource->getName(),'parent_id'=>$parent_id);
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();
                
              
            	$resource->setType($values['type']);
            	$resource->setName($values['name']);
            
            	
            	if(!is_null($values['parent_id']) && $values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Aclresources')->find($values['parent_id']);
                    $resource->setParent($parent);
            	}else{
            		$resource->setParent($resource);
            		
            	}
                
                $this->em->persist($resource);
                $this->em->flush();
               
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'aclresource', 'action' => 'index')).'">Back</a></p>';
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
        $resource = $this->em->getRepository('Aclresources')->find($id);

    	if($confirmed) {
            $this->em->remove($resource);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'Aclresources', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'Aclresources', 'action' => 'index'))."'>No</a>";
    	}
    }

}