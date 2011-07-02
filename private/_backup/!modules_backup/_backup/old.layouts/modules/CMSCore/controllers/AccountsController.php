<?php
/**
 * RoutesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_AccountsController
 * @subpackage AccountsController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_AccountsController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
{

	// make the seed for the random generator 
	private function makeSeed()
	{
	    list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000); 
	} 
	
	
	
	// make the password 
	function makePassword($pass_len = 8) 
	{ 
	  //seed the random generator 
	    mt_srand($this->makeSeed()); 
	  //create password
	   //excluded commonly mistaken characters and vowels to prevent spelling of offensive words
	    $excludes=array('a', 'e', 'i', 'o', 'u','1','l','0','O','U','V','v','I','A','E','J','j'); 
	    $password = "";
	    $loop=0; 
	    while($loop < $pass_len) 
	    { 
	    	$char='';
	        switch(mt_rand(0, 4)) //roughly proportion characters so that numbers do not dominate
	        { 
	            case 0: $char= mt_rand(0, 9);            break; // Number (0-9) 
	            case 1:
	            case 2: $char= chr(mt_rand(97, 122));    break; // Alpha Lower (a-z)
	            case 3:
	            case 4: $char= chr(mt_rand(65, 90));    break; // Alpha Upper (A-Z) 
	        }
		        if(!in_array($char,$excludes)){
	        	$password.=$char;
	        	$loop++;
	        }
	    }
	  return $password;
	}

	

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
		$this->view->users = $this->em->getRepository('Users')->findAll();
		$this->view->header = "Users";
    	$this->view->message = "Administer User Accounts";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $user = new Users;
        $form = new CMSCore_Form_Account();
        $return = "";
         
        $this->view->form = $form;     
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            $error=false;
            if(!empty($values['setpass1'])){
               if($values['setpass1']!=$values['setpass2']){
               	 $message="Passwords do not match";
               	 $return.=$message.'<br/>';
                 $passElement=$form->getElement('setpass1');
                 $passElement->addErrorMessage("Passwords do not match");
                 $error=true;
               }else{
               	 $password=$values['setpass1'];
               }
            }else{
            	$password=$this->makePassword() ;
            }
            if(!$error){
            	$user->setFirstname($values['firstname']);
            	$user->setLastname($values['lastname']);
            	$user->setUsername($values['username']);
            	$user->setEmail($values['email']);
            	$newrole = $this->em->getRepository('Roles')->find($values['role_id']);
            	$user->setRole($newrole);
            	$user->setSalt(md5($this->makePassword(16)));
            	$user->setPassword($password);
            	$this->em->persist($user);
            	$this->em->flush();
            	$this->view->form = "";
            	$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'accounts', 'action' => 'index'))."\">Added {$values['firstname']} {$values['lastname']}</a>";
 
            }
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
		$this->view->route = $this->em->getRepository('Users')->find($id);
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
        $user = $this->em->getRepository('Users')->find($id);
        $return = "";
        if(isset($user)) {
            $form = new CMSCore_Form_Account();
         
            #to do consider handling data base inconsistency if role table does not have entry for user
            $role=$user->getRole();
          
            $values = array('id' => $user->getId(), 'firstname' => $user->getfirstname(), 'lastname' => $user->getlastname(), 'email' => $user->getemail(),'role_id'=>$role->getId(),'username'=> $user->getUsername());
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();
                
                $error=false;
            	if(!empty($values['setpass1'])){
               		if($values['setpass1']!=$values['setpass2']){
               	 		$message="Passwords do not match";
               	 		$return.=$message.'<br/>';
                 		$passElement=$form->getElement('setpass1');
                 		$passElement->addErrorMessage("Passwords do not match");
                 		$error=true;
               		}else{
               	 		$password=$values['setpass1'];
               	 		$user->setSalt(md5($this->makePassword(16)));
            		    $user->setPassword($password);
               		}
            	}
            	if(!$error){
                	$user->setFirstname($values['firstname']);
                	$user->setLastname($values['lastname']);
                	$user->setUsername($values['username']);
                	$user->setEmail($values['email']);
                	$newrole = $this->em->getRepository('Roles')->find($values['role_id']);
          
					$user->setRole($newrole);
			      

                	$this->em->persist($user);
                	$this->em->flush();
                	
                
                	$return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'accounts', 'action' => 'index')).'">Back</a></p>';
            	}else{
            		$form->populate($values);
            	}
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
        $user = $this->em->getRepository('Users')->find($id);

    	if($confirmed) {
            $this->em->remove($user);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'accounts', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'routes', 'action' => 'index'))."'>No</a>";
    	}
    }

}