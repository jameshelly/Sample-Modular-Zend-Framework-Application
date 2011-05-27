<?php
/**
 * RoutesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_VersionablecontentController
 * @subpackage AccountsController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */




class CMSCore_VersionablecontentController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
{

    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	
    	$id=2;
        $verscontent = $this->em->getRepository('Versionablecontent')->find($id); 
    	
    	$versionManager = new DoctrineExtensions\WedVersionable\VersionManager($this->em);
        $versions = $versionManager->getVersions($verscontent); 

        $this->view->header = "Versions"; 
        $this->view->versions = $versions;
        
    }

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		//$this->view->users = $this->em->getRepository('Users')->findAll();
		//$this->view->header = "Users";  
		
    }
    
    
     /**
     * This action handles
     *    - testAction
     *    -
     */
    
    public function testAction() { 
     	  $form = new CMSCore_Form_Versionablecontent();
         
    	  $return = "";
          $this->view->form = $form;
          
          $status="not Allowed";
          $id=2;
          $verscontent = $this->em->getRepository('Versionablecontent')->find($id); 
          $values = array('id' => $verscontent->getId(), 'title' => $verscontent->getTitle(), 'content' => $verscontent->getContent());
          
        
          if(isset($verscontent)) {
            
            
            
          if($form->isValid($_POST)) {		
            $form->populate($_POST);
            $values = $form->getValues();
            
           // $verscontent = new DoctrineExtensions\Versionable\Versionablecontent; 
             
            
            $verscontent->setTitle($values['title']);
            $verscontent->setContent($values['content']);
            
//$eventManager = new EventManager();
//$eventManager->addEventSubscriber(new VersionListener());
//$em = EntityManager::create($connOptions, $config, $eventManager);

            
            
/*
Using the VersionManager you can now retrieve all the versions of a versionable entity:

$versionManager = new VersionManager($em);
$versions = $versionManager->getVersions($blogPost);
Or you can revert to a specific version number:

$versionManager = new VersionManager($em);
$versionManager->revert($blogPost, 100);*/

          
            
            
            $this->em->persist($verscontent);
            $this->em->flush();
            $this->view->form = "";          
          
  
          	$return .= "<p>";
          	$return .= "</p>";
          	$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'versionablecontent', 'action' => 'test'))."\">Next</a>&nbsp;&nbsp;" ;      
    	    $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'versionablecontent', 'action' => 'list'))."\">Version List</a>";       
          	
          	$this->view->messages = $return;
          }else{
          	
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
     *    - createAction
     *    -
     */
    public function createAction() {

    	
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
    	
    	
    }

    /**
     * This action handles
     *    - deleteAction
     *    -
     */
    public function deleteAction() {
    	
    	
    }
    
    


}