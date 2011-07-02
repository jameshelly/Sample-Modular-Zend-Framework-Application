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
 * @author     James A Helly <james@wednesday-london.com>
 * @version		0.1
 */

class CMSCore_PagesController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	//$this->indexAction();
    	//TODO Utilise nested list...
    	$id = $this->getRequest()->getParam('id');
    	$repo = $this->em->getRepository('Pages');
    	//$routes = $repo->findOneById($id);
    	$routes = $repo->findOneByAlias($id);
    	
    	#build tree.
    	$path = $repo->getPath($routes);
    	$kids = $repo->children($routes, true);
		$this->view->routes = $kids;//$path;//
		$this->view->header = "Pages";
    	$this->view->message = "Administer Pages : ".$repo->childCount($routes, true/*direct*/);//    	
    }

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
        $this->view->pages = $this->em->getRepository('Pages')->findAll();
        $this->view->header = "Pages";
    	$this->view->message = "Administer Pages";
    }

    /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {
        $page = new Pages;
        $form = new CMSCore_Form_Page();
        $return = "";
        $this->view->form = $form;
        //print "variable= ".$variable->getName()." type= $type <br/><br/><br/><br/><br/><br/><br/><br/>";
    	//to do get publish version
    	$publishVersion='alpha';
        if($form->isValid($_POST)) {
            $form->populate($_POST);
            $values = $form->getValues();
            if($values['parent_id'] > 0) {
                $parent = $this->em->getRepository('Pages')->find($values['parent_id']);
                $page->setParent($parent);
            }
            if($values['route_id'] > 0) {
                $route = $this->em->getRepository('Routes')->find($values['route_id']);
                $page->setRoute($route);
            }
            if($values['template_id'] > 0) {
                    $template = $this->em->getRepository('Templates')->find($values['template_id']);
                    $page->setTemplate($template);
                    $this->updatePageTemplate($page,$template,$publishVersion);
             
            }
            
            $page->setId($this->getUniqid());
            $page->setName($values['name']);
            $page->setAlias($values['alias']);
            $page->setSkin($values['skin']);
            $page->setOptions($values['options']);
            $created = new \DateTime("now");
            $page->setCreatedDate($created);
            $updated = new \DateTime($values['updated']);
            $page->setUpdatedDate($updated);
            $publishstartdate = new \DateTime($values['publishstartdate']);
            $page->setPublishStartDate($publishstartdate);
            $publishenddate = new \DateTime($values['publishenddate']);
            $page->setPublishEndDate($publishenddate);
            
            /* to do get and set current publish version */
            
            //the following needs to get the current working version name.
            //at the time of writing publish had not be written
            $page->setPublishVersion("alpha");
            
            $this->em->persist($page);
            
            
            
            $this->em->flush();
            $this->view->form = "";
            $return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'pages', 'action' => 'index'))."\">Added ".$values['name']."</a>";
        }
        $this->view->messages = $return;
    }

    /**
     * This action handles
     *    - readAction
     */
    public function readAction() {
    	$this->viewAction();
    }
    

    /**
     * This action handles
     *    - saveAction
     */
    public function saveAction() {
        $workFlowStatus = $this->getRequest()->getParam('work');
        if(empty($workFlowStatus)){
        	$workFlowStatus='work'; //could also be review or live
        }
    	$id = $this->getRequest()->getParam('id');
	    $page = $this->em->getRepository('Pages')->find($id);
    	$template=$page->getTemplate();      
	    $pageXml=Wednesday_Renderers_Template::xmlRender($template,$page);    
	    $pageVersion=$this->em->getRepository('Pageversions')->findOneBy(array('page'=>$page->getId(), 'workStatus'=>$workFlowStatus));
	   
	    if(empty($pageVersion)){
	    	$pageVersion=new Pageversions;
	    	$pageVersion->setId($this->getUniqid());
	    	$pageVersion->setPage($page);  //save page when created if always save will always update seems to be a doctrine feature!
	    }
	    
	    //$pageVersion->setPage($page);  //Do not place here causes update even if other records have not changed! 
	    $pageVersion->setBuild($pageXml);
	    $pageVersion->setAlias($page->getAlias());
	    $pageVersion->setName($page->getName());
	    $pageVersion->setPageVersion($page->getVersion());
	    $pageVersion->setWorkStatus($workFlowStatus);
	    $pageVersion->setPublishVersion($page->getPublishVersion());	
	  
	    
	    $this->em->persist($pageVersion);
        $this->em->flush();
	    	    
	    $this->view->message=$pageXml;
    
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
    	       'type' => $_POST['type'],
               'tablerow' => $tableRow
    	));
    	//do not add code here
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
    
    
    
    
    
    
    
     /**
     * This action handles
     *    - listAction
     *    -
     */
    public function editcontentsAction() {
        $workFlowStatus = $this->getRequest()->getParam('work');
        if(empty($workFlowStatus)){
        	$workFlowStatus='work'; //could also be review or live
        }
        $this->view->workFlowStatus=$workFlowStatus;
    	$pageId = $this->getRequest()->getParam('id');
	    $this->view->page = $this->em->find('Pages',$pageId);
	    $this->datacontents = $this->em->getRepository('Datacontent')->findby (array('page'=>$pageId));
	    $this->view->pageTemplate=$this->view->page->getTemplate();
	    $this->view->templatevariableassign=$this->em->getRepository('templatevariableassign');	
	    $this->view->pageId=$pageId; 
	   
        $pageVersion=$this->em->getRepository('Pageversions')->findOneBy(array('page'=>$pageId, 'workStatus'=>$workFlowStatus));
	
        $versionManager = new VersionManager($this->em);
        $this->view->versions = $versionManager->getVersions($pageVersion);
       
        
	    
        //$return.='<div id="result"></div>'; 
	     
	    $hideLinks='';
	  
	    $displayList=array();
	   
	    $ref=0; 
	    foreach($this->datacontents as $key => $datacontent){
	    	
	    	 $displayList[$key]['name']=$datacontent->getName();
			 $displayList[$key]['variableName']=$datacontent->getVariable()->getName();
			 $displayList[$key]['current']=	($datacontent->getCurrent()==0)?'no':'yes';
			 $textId="txtEditAreaID".$ref;
			 $linkOpenId="linkOpenId".$ref;
			 $linkCloseId="linkCloseId".$ref;
			 $displayList[$key]['edit']="
			 <div>
			 <a href='#' id='$linkOpenId' onclick=\"open_editor('$ref','datacontent','{$datacontent->getID()}')\">open editor</a>
			 <a href='#' id='$linkCloseId' onclick=\"close_editor('$ref','datacontent','{$datacontent->getID()}')\">save and close editor</a>
			 <textarea cols='100' rows='3' id='$textId' readonly='readonly' >{$datacontent->getContent()}</textarea>\n
			 
			 </div>
			 ";	
			 $ref++;
	    	 $hideLinks.=" \$j('#$linkCloseId').hide();\n";
	    	 
	    }
	    	
        $this->view->headScript()->prependFile('/assets/ckeditor/ckeditor.js');
        $this->view->headScript()->appendFile('/assets/ckeditor/adapters/jquery.js');
        
	   //this needs to be put into a separate js file and loaded
	   
        
        
        $editor=<<<herdocXXXX
        
        <script type="text/javascript"> 
          var editorReady=false;
          var editCount=0;
          var editRef=new Array();
          
         \$j(document).ready(function() {
            
             $hideLinks
             
          });
          
       CKEDITOR.plugins.registered['save']=
       {
           init : function( editor )
           {
              var command = editor.addCommand( 'save', 
                 {
                    modes : { wysiwyg:1, source:1 },
                    exec : function( editor ) {
                        txtEditAreaID=editor.name;
                        ref=txtEditAreaID.substring(13);
                        saveContents(txtEditAreaID,editRef[ref]['type'], editRef[ref]['id'] );
                       //alert("here"+ ref + "id=" + editRef[ref]['id']  + " type=" +editRef[ref]['type'] + " content=" + );
                    }
                 }
              );
              editor.ui.addButton( 'Save',{label : 'My Save',command : 'save'});
           }
        }
          

        function open_editor(ref ,datatype,id){
        	 txtAreaId="txtEditAreaID"+ref;	
           	 
			 linkOpenId="linkOpenId"+ref;
			 linkCloseId="linkCloseId"+ref;
			 \$j("#"+linkOpenId).hide();
			 \$j("#"+linkCloseId).show();
			 
             editRef[ref]= {type:datatype, id:id};        
             ckedit(txtAreaId);     		
        
        }
        
          function close_editor(ref, datatype,id){
        	 txtAreaID="txtEditAreaID"+ref;	
           	 
			 linkOpenId="linkOpenId"+ref;
			 linkCloseId="linkCloseId"+ref;
			 \$j("#"+linkOpenId).show();
			 \$j("#"+linkCloseId).hide();
			 saveContents(txtAreaID,datatype, id);
             var editor = \$j("#"+txtAreaID).ckeditorGet();
             // Destroy the editor.
	         editor.destroy();
	         editor = null;
        
        }
        

          
          
        function ckedit(textAreaId){
         
            \$j("#"+textAreaId).ckeditor( function() {
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
          }
          
          function saveContents(txtAreaID,datatype, id){
                 var contentdata = \$j("#"+txtAreaID).val();
                 var contenttype = datatype;
                 var contentid = id;
  
                 var postvars={
                 		"content": contentdata,
                 		"id"     : contentid,
                 		"type"   : contenttype
                 
                 };                         
                  \$j.ajax({
                     url: '/admin/pages/ajax',
                     dataType: "json",
                     data: postvars,
                     async: true,
                     type: "POST",
                  	 success: function(data){
                           \$j('#result').html(data.reply);
                                        	 
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

          function rollback(pageId,versionId,workStatus){
          	 var postvars={
                 		"versionid"  : versionId,
                 		"pageid"     : pageId,
                 		"workstatus" : workStatus
             };  
			\$j.ajax({
                     url: '/admin/pages/rollback',
                     dataType: "json",
                     data: postvars,
                     async: true,
                     type: "POST",
                  	 success: function(data){
                  	     alert(data.reply);
                           \$j('#result').html(data.reply);  
                          
                           
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
  
	    	 	    
	    $this->view->ckeditor = $editor; 	    
	    $this->view->displayList=$displayList;      
    }
    
    
      /**
     * This action handles
     *    - rollbackAction
     *    - viewAction
     */
    public function rollbackAction() {
    	
    	$pageId=$_POST['pageid'];
    	$toVersionNum = $_POST['versionid'];
    	$workFlowStatus =  $_POST['workstatus'];
    	
    	//$pageId='4dd3c2bc136954bce1';
    	//$toVersionNum =  6;
    	//$workFlowStatus =  'work'; 
    	$page = $this->em->getRepository('Pages')->find($pageId);
    	$pageVersion=$this->em->getRepository('Pageversions')->findOneBy(array('page'=>$pageId, 'workStatus'=>$workFlowStatus));
	
        $versionManager = new VersionManager($this->em);
        $versions = $versionManager->getVersions($pageVersion);
        
        if (!isset($versions[$toVersionNum])) {
           throw Exception::unknownVersion($toVersionNum);
        }
       
        $version = $versions[$toVersionNum];
        //$reply="version $toVersionNum has been copied to editor please save to make current";
		
        //$count=count( $versions);
        
		$dataRecord=$version->getVersionedData();
		
		
       //print_r($dataRecord);

       
       
        $doc = DOMDocument::loadXML($dataRecord['build']);
        
        $variables=$doc->getElementsByTagName('variable');
        foreach($variables as $variable){
        	if($variable->hasAttribute('id')){
        		$varId=$variable->getAttribute('id');
        		if($variable->hasAttribute('type')){
        			  $type=$variable->getAttribute('type');
        			  switch($type){
        			  	case 'pagelongtext':
        			  	case 'pageshorttext':
        			  		//page long text should have data content inside content tags
        			  		 $contents=$variable->getElementsByTagName('content');
                             if(count($contents)==1)
        			  		 $contents=$contents->item(0)->nodeValue;
        			  		 //now restore datacontent        			  	
                             $dataContent=$this->em->getRepository('Datacontent')->findOneBy(array('page'=>$pageId, 'variable'=>$varId));
        			         $dataContent->setContent($contents);		 
                             $this->em->persist($dataContent);
        			  		break;
        			  	
        			  	
        			  }
        		}
        	}
        	
        	
        	
        }
         
       
    	$template=$page->getTemplate();      
	    $pageXml=Wednesday_Renderers_Template::xmlRender($template,$page);    
	   	
	    $pageVersion->setBuild($pageXml);
	    $pageVersion->setAlias($page->getAlias());
	    $pageVersion->setName($page->getName());
	    $pageVersion->setPageVersion($page->getVersion());
	    $pageVersion->setWorkStatus($workFlowStatus);
	    $pageVersion->setPublishVersion($page->getPublishVersion());	
	  
	    
	    $this->em->persist($pageVersion);
        $this->em->flush();
	    $reply="done";
		
  
  
    	$this->_helper->json(array('content' => $dataRecord['alias'],
    	                           'id'=>$pageId,
    	                            'work'=>$workFlowStatus,
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
        $page = $this->em->getRepository('Pages')->find($id);
        $return = "";
        //print "variable= ".$variable->getName()." type= $type <br/><br/><br/><br/><br/><br/><br/><br/>";
    	//to do get publish version
    	$publishVersion='alpha';
        if(isset($page)) {
            $form = new CMSCore_Form_Page();
            $parent = $page->getParent();
            $route = $page->getRoute();
            $template=$page->getTemplate();
            $parent_id = (null !== $parent)?$parent->getId():0;
            $route_id = (null !== $route)?$route->getId():0;
            $template_id =(null !== $template)?$template->getId():0;
            $values = array(
                'id' => $page->getId(),
                'name' => $page->getName(),
                'alias' => $page->getAlias(),
                'skin' => $page->getSkin(),
                'options' => $page->getOptions(),
                'parent_id' => $parent_id,
                'route_id' => $route_id,
           		'template_id'=> $template_id,
                'created' => $page->getCreatedDate()->format('d m Y'),
                'updated' => $page->getUpdatedDate()->format('d m Y'),
                'publishstartdate' => $page->getPublishStartDate()->format('d m Y'),
                'publishenddate' => $page->getPublishEndDate()->format('d m Y')
            );
            if($form->isValid($_POST)) {
                $form->populate($_POST);
                $values = $form->getValues();

                if($values['parent_id'] > 0) {
                    $parent = $this->em->getRepository('Pages')->find($values['parent_id']);
                    $page->setParent($parent);
                }
                if($values['route_id'] > 0) {
                    $route = $this->em->getRepository('Routes')->find($values['route_id']);
                    $page->setRoute($route);
                }
                if($values['template_id'] > 0) {
                    $template = $this->em->getRepository('Templates')->find($values['template_id']);
                    $page->setTemplate($template);
                    $this->updatePageTemplate($page,$template,$publishVersion);
             
                }
                $page->setName($values['name']);
                $page->setAlias($values['alias']);
                $page->setSkin($values['skin']);
                $page->setOptions($values['options']);
                 
//                $created = new \DateTime("now");
//                $page->setCreatedDate($created);
                $updated = new \DateTime(strtotime($values['updated']));
                $page->setUpdatedDate($updated);
                $publishstartdate = new \DateTime(strtotime($values['publishstartdate']));
                $page->setPublishStartDate($publishstartdate);
                $publishenddate = new \DateTime(strtotime($values['publishenddate']));
                $page->setPublishEndDate($publishenddate);
                $this->em->persist($page);
                $this->em->flush();
                $return .= 'Updated, now go <a href="'.$this->view->url(array('module' => 'CMSCore', 'controller' => 'pages', 'action' => 'index')).'">Back</a></p>';
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
            $this->view->message = "Are you sure you want to delete this item? <a href='".$this->view->url(array('action' => 'delete'))."?page=$id&amp;confirm=true'>Yes</a>/<a href='".$this->view->url(array('module' => 'CMSCore', 'controller' => 'pages', 'action' => 'index'))."'>No</a>";
    	}
    }
    


    
protected function  updatePageTemplate($page,$template,$publishVersion){
    	
    	
    	//but first we can set all page datacontent to not current
        $dataContents=$this->em->getRepository('Datacontent')->findBy(array('page'=>$page->getId()));
        foreach($dataContents as $dataContent){
        	 $dataContent->setCurrent(0);
        	 $this->em->persist($dataContent);$this->em->persist($dataContent);
        }
  
    	
      $this->updateContentFromVariables($page,$template,$publishVersion);
    	
  	
}
    

    

}