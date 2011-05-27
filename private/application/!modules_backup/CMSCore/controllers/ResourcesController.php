<?php
/**
 * ResourcesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_ResourcesController
 * @subpackage ResourcesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     James A Helly <james@wednesday-london.com>
 * @version		0.1
 */

class CMSCore_ResourcesController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface {

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		$this->view->header = "Resources";
    	$this->view->message = "Administer Resources";
		$this->view->actions = $this->em->getRepository('Actions')->findAll();
		$this->log->info(get_class($this).'::indexAction()');
    }

	/**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	$this->indexAction();
		$this->log->info(get_class($this).'::listAction()');
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
		$action = new Actions;
		$form = new CMSCore_Form_Action();
		$return = "";
        $this->view->form = $form;
		if($form->isValid($_POST)) {
			$form->populate($_POST);
			$values = $form->getValues();
			$action->setName($values['name']);
			$action->setDescription($values['description']);
			$this->em->persist($action);
			$this->em->flush();
			$this->view->form = "";
			$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'resources', 'action' => 'index'))."\">Added ".$values['name']."</a>";
		}
        $this->view->messages = $return;
    	$this->log->info(get_class($this).'::createAction()');
    }

    /**
     * This action handles
     *    - readAction
     *    - viewAction
     */
    public function readAction() {
    	$id = $this->getRequest()->getParam('id');
		$this->view->action = $this->em->getRepository('Actions')->find($id);
    	$this->log->info(get_class($this).'::readAction()');
    }

    /**
     * This action handles
     *    - readAction
     *    - viewAction
     */
    public function viewAction() {
		$this->log->info(get_class($this).'::readAction()');
    	$this->readAction();
    }

    /**
     * This action handles
     *    - updateAction
     *    - editAction
     */
    public function updateAction() {
		$this->log->info(get_class($this).'::updateAction()');
		$this->editAction();
    }

    /**
     * This action handles
     *    - updateAction
     *    - editAction
     */
    public function editAction() {
    	$id = $this->getRequest()->getParam('id');
		$action = $this->em->getRepository('Actions')->find($id);
		$return = "";
		if(isset($action)) {
			$form = new CMSCore_Form_Action();
			$values = array('id' => $action->getId(), 'name' => $action->getName(), 'description' => $action->getDescription());
			if($form->isValid($_POST)) {
				$form->populate($_POST);
				$values = $form->getValues();
				$action->setName($values['name']);
				$action->setDescription($values['description']);
				$this->em->persist($action);
				$this->em->flush();
				$return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'resources', 'action' => 'index')).'">Back</a></p>';
			} else {
				$form->populate($values);
			}
		} else {
    		$return = "No Results";
    	}
        $this->view->messages = $return;
        $this->view->form = $form;
    	$this->log->info(get_class($this).'::editAction()');
    }

    /**
     * This action handles
     *    - deleteAction
     *    -
     */
    public function deleteAction() {
    	$id = $this->getRequest()->getParam('id');
    	$confirmed = $this->getRequest()->getParam('confirm');
		$action = $this->em->getRepository('Actions')->find($id);

    	if($confirmed) {
			$this->em->remove($action);
			$this->em->flush();
    		$this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'resources', 'action' => 'index'))."'>Back</a>";
    	} else {
	        $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'resources', 'action' => 'index'))."'>No</a>";
    	}
    	$this->log->info(get_class($this).'::deleteAction()');
    }

}