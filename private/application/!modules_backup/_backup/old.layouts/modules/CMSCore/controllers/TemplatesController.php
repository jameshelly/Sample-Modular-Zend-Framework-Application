<?php
/**
 * PagesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_PagesController
 * @subpackage PagesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_TemplatesController extends Wednesday_Controller_Action {

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
        $this->view->templates = $this->em->getRepository('Templates')->findAll();
        $this->view->header = "Templates";
    	$this->view->message = "Administer Templates";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $template = new Templates;
        $form = new CMSCore_Form_Templates();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            $template->setId($this->getUniqid());
            $template->setName($values['name']);
            $template->setType($values['type']);
            $template->setContent($values['content']);
           
            /* to do get and set current publish version */
            
            //the following needs to get the current working version name.
            //at the time of writing publish had not be written
            $template->setPublishVersion("alpha");
            
            $this->em->persist($template);
            //$this->em->flush();
            
            //update copy on disk used to render page and set up content variables        
            $this->saveTemplate($values['name'],$values['content'],$template);
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'templates', 'action' => 'index'))."\">Added ".$values['name']."</a>";
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
	    $this->view->templates = $this->em->getRepository('Templates')->find($id);
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
        $template = $this->em->getRepository('Templates')->find($id);
        $return = "";
        if(isset($template)) {
            $form = new CMSCore_Form_Templates();
            $values = array(
                'id' => $template->getId(),
                'name' => $template->getName(),
                'type' => $template->getType(),
                'content' => $template->getContent()
                
            );
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();

               
                $template->setName($values['name']);
                $template->setType($values['type']);
                $template->setcontent($values['content']);
                
               
                $this->em->persist($template);
                //$this->em->flush();
                 
                $this->saveTemplate($values['name'],$values['content'],$template);
             
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'templates', 'action' => 'index')).'">Back</a></p>';
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
        $page = $this->em->getRepository('Pages')->find($id);

    	if($confirmed) {
            $this->em->remove($page);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'pages', 'action' => 'index'))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'pages', 'action' => 'index'))."'>No</a>";
    	}
    }
    
   
    protected function saveTemplate($name,&$content,&$template){	
       $variableList=array();
       $order=0;
       //this function returns the php code for a matched variable and stores each
       //variable name in the index of an  array.
       $callbackreplace=function($matches) use (&$variableList,&$order){
       	  if(!isset($variableList[$matches[1]])){
       	        $variableList[$matches[1]]=$order++; 
       	  }
       	  return "<?php print \$this->placeholder('{$matches[1]}'); ?>";
       };
       //now use call back to find and replace variables between { } a variable can have alphanumerics _ and -
       $replacedContent=preg_replace_callback('@{\s*?([a-zA-z0-9_\-]*?)\s*}@smx',$callbackreplace,$content);     
       //update copy on disk used to render page
       file_put_contents(APPLICATION_PATH.'/usertemplates/scripts/'.$name.'.phtml',$replacedContent);
         //the following needs to get the current working version name.
        //at the time of writing publish had not be written
       $publishVersion= "alpha";
   // print_r($variableList);   
       //now check/add variables to database and assign new content when required
       foreach($variableList as $variableName=>$order){
       	   $variable = $this->em->getRepository('Variables')->findOneBy(array('name'=>$variableName));
       	   if(empty($variable)){
       	   	     $variable = new Variables;
       	   	     $variable->setId($this->getUniqid()); 
       	   	     $variable->setName($variableName);
                 $variable->setType('undefined');
                 $variable->setFilter('');
                 /**
                  * @todo get current published version
                  */
            	 
            	$variable->setPublishVersion($publishVersion);
            	$templateAssign= new Templatevariableassign;
            	$templateAssign->setId($this->getUniqid()); 
            	$templateAssign->setVariable($variable);
            	$templateAssign->setTemplate($template);
            	$templateAssign->setVariableOrder($order);
            	$templateAssign->setPublishVersion($publishVersion);
            	
            	//$variable->addTemplate($template);
            	//$template->addVariable($variable);
            	//$this->em->persist($template);
            	$this->em->persist($variable);
            	$this->em->persist($templateAssign);
            	
       	   }else{
       	   	  //see if variable has been assigned to template
       	   	  $templateAssign=$this->em->getRepository('Templatevariableassign')->findOneBy(array('variable'=>$variable->getId(),'template'=>$template->getId()));
       	   	  
       	   	  if(empty($templateAssign)){
       	   	  	 //known variable but not assigned so assign it to template
       	   	  	 $templateAssign= new Templatevariableassign;
       	   	  	 $templateAssign->setId($this->getUniqid());   
            	 $templateAssign->setVariable($variable);
            	 $templateAssign->setTemplate($template);
            	 $templateAssign->setVariableOrder($order);
            	 $templateAssign->setPublishVersion($publishVersion);
            	 $this->em->persist($templateAssign);
       	   	  	
       	   	  }elseif($templateAssign->getVariableOrder() != $order){
       	   	  	 //already assigned but order is incorrect so change it.
       	   	  	 $templateAssign->setVariableOrder($order);
       	   	  	 $this->em->persist($templateAssign);
       	   	  }
       	   	
       	   	
       	   }
       	
       }
       $this->em->flush();
       
       
       
    	
    }
    

}