<?php

use DoctrineExtensions\WedVersionable\VersionManager;

/**
 * PagesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_PagesController
 * @subpackage PagesController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin Marsh (martinmarsh@sygenius.com)
 * @version		0.1
 */

class CMSCore_DataContentController extends Wednesday_Controller_Action {


    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	$pageId = $this->getRequest()->getParam('id');
	    $this->view->page = $this->em->find('Pages',$pageId);
	    $this->view->datacontents = $this->em->getRepository('Datacontent')->findby (array('page'=>$pageId));
	    $this->view->pageTemplate=$this->view->page->getTemplate();
	    $this->view->templatevariableassign=$this->em->getRepository('templatevariableassign');	
	    $this->view->pageId=$pageId;   
    }

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
     *    - createAction
     *    -
     */
    public function createAction() {  	
        $datacontent = new Datacontent;
        $form = new CMSCore_Form_Datacontent();
        $return = "";
        $this->view->form = $form;
        if($form->isValid($_POST)) {
        	$pageId = $this->getRequest()->getParam('id');
	        $this->page = $this->em->find('Pages',$pageId);
            $form->populate($_POST);
            $values = $form->getValues();
           
            $datacontent->setId($this->getUniqid());
            $datacontent->setName($values['name']);
            $datacontent->setContent($values['content']);

            //at the time of writing publish had not be written
            $datacontent->setPublishVersion($this->page->getPublishVersion());
            $datacontent->setPage($this->page);
            $datacontent->setCurrent(1);
            $this->em->persist($datacontent);
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'content', 'action' => 'list'))."\">Added ".$values['name']."</a>";
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
		$this->view->page = $this->em->getRepository('Pages')->find($id);
		
    }

    /**
     * This action handles
     *    - updateAction
     *    - editAction
     */
    public function updateAction() {
    	$this->editAction();
    }

    public function ajaxAction() {	
    	
     	//note we will assume there is no security issue by using post values
     	//here as access to this action should be permission based.
     	//
     	//Do we need to check acl permission here or is there a generic url protection in place?
     	//
     	//however we should reaLLY ADD A NONCE or other verification method
     	//to ensure a login used is not tricked into updating
     	$id=$_POST['id'];
     	$datacontent = $this->em->getRepository('Datacontent')->find($id);
        $datacontent->setContent($_POST['content']);
        $versionManager = new VersionManager($this->em);
        $curVersion=$versionManager->getLatestVersionNumber($datacontent);
        
        //must persist and flush to create new version in versionable table
        $this->em->persist($datacontent);
        $this->em->flush();	   
        $newVersion=$versionManager->getLatestVersionNumber($datacontent);
        
        if($newVersion==$curVersion){
        	$reply="no changes to save"; 
	        $jsAction='';
	        $tableRow='';
        	
        }else{
   
	        $versions = $versionManager->getVersions($datacontent);
	        $version=array_shift($versions);
	        $verdata= $version->getVersionedData();
	        
	        $verDate=$version->getSnapshotDate()->format("d M Y  H:i");
	        $delete=$this->view->baseUrl();
	        $delete.=$this->view->url(array('action' => 'delete', 'id' => $version->getId()));
	        
	        $reply="saved"; 
	        $jsAction='newversion';
        
	        
        //this is quick and dirty we need to put this in a view but not sure if we will be
        //using extJs instead of HTML tables so I will leave it here as could be
        //that we just need to return the json object

       
        $tableRow=<<<heredocXXXX
        
        <tr>
				<td>{$version->getVersion()}</td>
		        <td>{$version->getPublishVersion()}</td>
		        <td>{$verDate}</td>
			    	
				<td><a class="ui-icon ui-icon-star" title="roll back" href="#" onclick="return rollback('$id','{$version->getVersion()}');" >rollback</a></td>
				<td><a class="ui-icon ui-icon-close" title="delete" href="$delete?contentid=$id">delete</a></td>
		</tr>
        
heredocXXXX;
        }


        //note this terminates the action and replies
        $this->_helper->json(array(
    	       'reply'=>$reply,
               'jsaction' => $jsAction,
    	       'id' =>$_POST['id'],
    	       'name' => $_POST['name'],
               'tablerow' => $tableRow
    	));
    	//do not add code here
    }
    
      /**
     * This action handles
     *    - rollbackAction
     *    - viewAction
     */
    public function rollbackAction() {
    	
    	$contentid=$_POST['contentid'];
    	$datacontent = $this->em->getRepository('Datacontent')->find($contentid);
    	$toVersionNum = $_POST['versionid'];
    	
    	$versionManager = new VersionManager($this->em);
        $versions = $versionManager->getVersions($datacontent);
      
        if (!isset($versions[$toVersionNum])) {
           throw Exception::unknownVersion($toVersionNum);
        }
        /* @var $version Entity\ResourceVersion */
        $version = $versions[$toVersionNum];
        $reply="version $toVersionNum has been copied to editor please save to make current";
		
		$dataRecord=$version->getVersionedData();
		
    	$this->_helper->json(array('content' => $dataRecord['content'],
    	                           'id'=>$contentid,
    	                            'ver'=>$toVersionNum,
    	                            'reply'=>$reply
    	                    ));
    }
    
    
    /**
     * This action handles
     *    - editAction
     *    -
     */
    
     
    public function editAction() { 
    	
    	$id = $this->getRequest()->getParam('id');
        $datacontent = $this->em->getRepository('Datacontent')->find($id);
        $return = "";
        $this->view->contentId=$id;
        $versionManager = new VersionManager($this->em);
        $this->view->versions = $versionManager->getVersions($datacontent);
       
        
        if(isset($datacontent)) {
            $form = new CMSCore_Form_Datacontent();        
            $values = array(
                'id' => $datacontent->getId(),
                'name' => $datacontent->getName(),
                'content' => $datacontent->getContent(),
                
            );
       
            $form->populate($values);
            /* 
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();
                $datacontent->setName($values['name']);
                $datacontent->setContent($values['content']);        
            	$this->em->persist($datacontent);
            	$this->em->flush();
	
              } else {
                $form->populate($values);
            }*/
            $return .= '<a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'datacontent', 'action' => 'list')).'">Back</a></p>';
            
        } else {
        $return = "No Results";
    	}
    	
    	
        $this->view->headScript()->prependFile('/assets/ckeditor/ckeditor.js');
        $this->view->headScript()->appendFile('/assets/ckeditor/adapters/jquery.js');
        //$this->view->headLink()->appendStylesheet('');
        
        //this needs to be put into a separate js file and loaded
        $editor=<<<herdocXXXX
        
        <script type="text/javascript"> 
            \$j(document).ready(function() {
            var editorReady=false;
            \$j("#textarea_id").ckeditor( function() {
						editorReady=true;
                      }, { 
                      toolbar :
		    		  [
						['Source','-','Save'],
		    			['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker', 'Scayt'],
		    			['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		    			'/',
		    			['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		    			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		    			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		    			['Link','Unlink','Anchor'],
		    			['Image','HorizontalRule','SpecialChar'],
		    			'/',
		    			['Styles','Format'],
		    			['Maximize', 'ShowBlocks','-','About']
		    		  ]
             } );

             
          });
          
          function saveContents(datatype, name){
                 var contentdata = \$j("#textarea_id" ).val();
                 var contentname = \$j("#contentname_id").val();
                 var contentid = \$j("#contentid_id").val();
  
                 var postvars={
                 		"content": contentdata,
                 		"id"     : contentid,
                 		"name"   : contentname
                 
                 };                         
                  \$j.ajax({
                     url: '/admin/datacontent/ajax',
                     dataType: "json",
                     data: postvars,
                     async: true,
                     type: "POST",
                  	 success: function(data){
                           \$j('#result').html(data.reply);
                           if(data.jsaction=='newversion'){
                           		\$j('#versiontable_id tbody').prepend(data.tablerow);
                           }                 	 
                  	 },
                  	 complete: function(jqXHR,textStatus){
                  	      if(textStatus!='success'){
                  	            alert("Warning there was a problem saving; "+textStatus);
                  	           \$j('#result').html("Error: There was a problem saving.");
                  	                            	          
                  	      }             	      
                  	 }                		
				  });				  
               return false;          
          }   

          function rollback(contentId,versionId){
          	 var postvars={
                 		"versionid"  : versionId,
                 		"contentid"  : contentId
             };  
			\$j.ajax({
                     url: '/admin/datacontent/rollback',
                     dataType: "json",
                     data: postvars,
                     async: true,
                     type: "POST",
                  	 success: function(data){
                           \$j('#result').html(data.reply);  
                           \$j("#textarea_id" ).val(data.content);
                           
                  	 },
                  	 complete: function(jqXHR,textStatus){
                  	      if(textStatus!='success'){
                  	            alert("Warning there was a problem saving; "+textStatus);
                  	           \$j('#result').html("Error: There was a problem saving.");                 	          
                  	      }             	      
                  	 }                		
				  });		
             
          
             return false;
          }
          
          
         </script>
         
     
herdocXXXX;
//do not indent above line!
      
        $return.='<div id="result"></div>';
       
		$this->view->ckeditor = $editor;
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
        $pageId=$this->getRequest()->getParam('pageid');
    	$confirmed = $this->getRequest()->getParam('confirm');
        $object = $this->em->getRepository('Datacontent')->find($id);

    	if($confirmed) {
            $this->em->remove($object);
            $this->em->flush();
            $this->view->message = "Deleted, now go <a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'datacontent', 'action' => 'list', 'id'=>$pageId))."'>Back</a>";
    	} else {
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete', 'id' => $id))."?confirm=true&amp;pageid=$pageId'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'datacontent', 'action' => 'list', 'id'=>$pageId))."'>No</a>";
    	}
    }

}