<?php
/**
 * RoutesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_RoutesController
 * @subpackage RoutesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     James A Helly <james@wednesday-london.com>
 * @version		0.1
 */

class CMSCore_RoutesController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
{

    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	//$this->indexAction();
    	//TODO Utilise nested list...
    	$id = $this->getRequest()->getParam('id');
    	$repo = $this->em->getRepository('Routes');
    	//$routes = $repo->find($id);
    	$routes = $repo->findOneByRoute('/');
    	
    	#build tree.
    	$path = $repo->getPath($routes);
    	$kids = $repo->children($routes, true);
		$this->view->routes = $kids;//$path;//
		$this->view->header = "Routes";
    	$this->view->message = "Administer Routes : ".$repo->childCount($routes, true/*direct*/);//
    }

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		$this->view->routes = $this->em->getRepository('Routes')->findAll();
		$this->view->header = "Routes";
    	$this->view->message = "Administer Routes";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $route = new Routes;
        $form = new CMSCore_Form_Route();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            if($values['parent_id'] > 0) {
                $parent = $this->em->getRepository('Routes')->find($values['parent_id']);
                $route->setParent($parent);
            }else{
            	 $query = $this->em->createQuery("SELECT v FROM Routes v WHERE v.parent=v");
        		 $topRoute=array_shift($query->getResult());
        		 if(empty($topRoute)){
        		 	$topRoute=new Routes;
        		 	$topRoute->setName('root');
        		 	$topRoute->setRoute('');
        		 	$topRoute->setParent($topRoute);
    
        		 	$topRoute->setDescription('Top of directory tree - auto selected for no parent');
                    $this->em->persist($topRoute);
        		 	
        		 }
        		 $route->setParent($topRoute);
            }
            $route->setName($values['name']);
            $route->setRoute($values['route']);
            $route->setDescription($values['description']);
            $this->em->persist($route);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'routes', 'action' => 'index'))."\">Added ".$values['name']."</a>";
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
		$this->view->route = $this->em->getRepository('Routes')->find($id);
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
        $route = $this->em->getRepository('Routes')->find($id);
        $return = "";
        if(isset($route)) {
            $form = new CMSCore_Form_Route();
            $parent = $route->getParent();
            $parent_id = (null !== $parent)?$parent->getId():0;
            $values = array('id' => $route->getId(), 'name' => $route->getName(), 'parent_id' => $parent_id, 'route' => $route->getRoute(), 'description' => $route->getDescription());
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();
                $parent = $this->em->getRepository('Routes')->find($values['parent_id']);
                if($values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Routes')->find($values['parent_id']);
                    $route->setParent($parent);
                }else{
            	 	$query = $this->em->createQuery("SELECT v FROM Routes v WHERE v.parent=v");
        		 	$topRoute=array_shift($query->getResult());
        		 	if(empty($topRoute)){
        		 		$topRoute=new Routes;
        		 		$topRoute->setName('root');
        		 		$topRoute->setRoute('');
        		 		$topRoute->setParent($topRoute);
        		 		$topRoute->setDescription('Top of directory tree - auto selected for no parent');
                    	$this->em->persist($topRoute);
        		 	
        		 	}
        		 	$route->setParent($topRoute);
            	}
                $route->setName($values['name']);
                $route->setRoute($values['route']);
                $route->setDescription($values['description']);
                $this->em->persist($route);
                $this->em->flush();
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'routes', 'action' => 'index')).'">Back</a></p>';
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
        $route = $this->em->getRepository('Routes')->find($id);

    	if($confirmed) {
            $this->em->remove($route);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'routes', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'routes', 'action' => 'index'))."'>No</a>";
    	}
    }

}