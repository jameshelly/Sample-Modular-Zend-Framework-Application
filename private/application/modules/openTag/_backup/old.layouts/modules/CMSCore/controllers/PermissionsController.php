<?php
/**
 * PermissionsController - The default error controller class
 *
 * @category	Admin
 * @package		CMSCore
 * @subpackage	PermissionsController
 * @copyright	copyright_declaration
 * @license		license_declaration
 * @author		mrhelly
 * @version		version
 *
 */

class CMSCore_PermissionsController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface {

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
    	$this->listAction();
    }

	/**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
		$this->view->permissions = $this->em->getRepository('Permissions')->findAll();
		$this->view->header = "Permissions";
    	$this->view->message = "Administer Permissions";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
		$permission = new Permissions;
		$form = new CMSCore_Form_Permission();
		$return = "";
        $this->view->form = $form;
		if($form->isValid($_POST)) {
			$form->populate($_POST);
			$values = $form->getValues();
			$newaction = $this->em->getRepository('Actions')->find($values['action_id']);
			$permission->setAction($newaction);
			$permission->setAllowed($values['allowed']);
			$this->em->persist($permission);
			$this->em->flush();	
			$this->view->form = "";
			$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'permissions', 'action' => 'index'))."\">Added ".$newaction->getName()."</a>";
		}
        $this->view->messages = get_class($this).'::createAction()'.$return;
        $this->view->form = $form;
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
     *    - readAction
     *    - viewAction
     */
    public function viewAction() {
    	$id = $this->getRequest()->getParam('id');
		$this->view->permission = $this->em->getRepository('Permissions')->find($id);
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
     *    - updateAction
     *    - editAction
     */
    public function editAction() {
    	$id = $this->getRequest()->getParam('id');
		$permission = $this->em->getRepository('Permissions')->find($id);
		$return = "";
		if(isset($permission)) {
			$form = new CMSCore_Form_Permission();
			$action = $permission->getAction();
			$allowed = ($permission->getAllowed()==1)?'allowed':'denied';
			$values = array('id' => $permission->getId(), 'action_id' => $action->getId(), 'allowed' => $permission->getAllowed());
			if($form->isValid($_POST)) {
				$form->populate($_POST);
				$values = $form->getValues();
				$newaction = $this->em->getRepository('Actions')->find($values['action_id']);
				$permission->setAction($newaction);
				$permission->setAllowed($values['allowed']);
				$this->em->persist($permission);
				$this->em->flush();
				$return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'permissions', 'action' => 'index')).'">Back</a></p>';
				
			} else {
				$form->populate($values);
			}
		}
        $this->view->messages = get_class($this).'::updateAction()'.$return;
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
		$permission = $this->em->getRepository('Permissions')->find($id);

    	if($confirmed) {
			$this->em->remove($permission);
			$this->em->flush();
    		$this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'permissions', 'action' => 'index'))."'>Back</a>";
    	} else {
	        $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'permissions', 'action' => 'index'))."'>No</a>";
    	}
    }

}