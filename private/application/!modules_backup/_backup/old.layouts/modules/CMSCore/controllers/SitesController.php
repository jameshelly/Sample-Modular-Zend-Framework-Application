<?php
/**
 * SitesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_SitesController
 * @subpackage SitesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     James A Helly <james@wednesday-london.com>
 * @version		0.1
 */

class CMSCore_SitesController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
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
		$this->view->sites = $this->em->getRepository('Sites')->findAll();
		$this->view->header = "Brands";
    	$this->view->message = "Administer Brands";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
		$site = new Sites;
		$form = new CMSCore_Form_Site();
		$return = "";
        $this->view->form = $form;
		if($form->isValid($_POST)) {
			$form->populate($_POST);
			$values = $form->getValues();
			$site->setName($values['name']);
			$site->setDescription($values['description']);
			$this->em->persist($site);
			$this->em->flush();
			$this->view->form = "";
			$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'sites', 'action' => 'index'))."\">Added ".$values['name']."</a>";
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
		$this->view->site = $this->em->getRepository('Sites')->find($id);
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
		$site = $this->em->getRepository('Sites')->find($id);
		$return = "";
		if(isset($site)) {
			$form = new CMSCore_Form_Site();
			$values = array('id' => $site->getId(), 'name' => $site->getName(), 'description' => $site->getDescription());
			if($form->isValid($_POST)) {
				$form->populate($_POST);
				$values = $form->getValues();
				$site->setName($values['name']);
				$site->setDescription($values['description']);
				$this->em->persist($site);
				$this->em->flush();
				$return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'sites', 'action' => 'index')).'">Back</a></p>';
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
		$site = $this->em->getRepository('Sites')->find($id);

    	if($confirmed) {
			$this->em->remove($site);
			$this->em->flush();
    		$this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'sites', 'action' => 'index'))."'>Back</a>";
    	} else {
	        $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'sites', 'action' => 'index'))."'>No</a>";
    	}
	}

}