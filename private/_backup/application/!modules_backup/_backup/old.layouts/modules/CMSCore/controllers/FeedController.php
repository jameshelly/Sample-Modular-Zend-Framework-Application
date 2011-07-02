<?php

/**
 * FeedController - 
 * 
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_PagesController
 * @subpackage FeedController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin Marsh (martinmarsh@sygenius.com)
 * @version		0.1
 */

class CMSCore_FeedController extends Wednesday_Controller_Action {

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
		$channel = new Zend_Feed_Rss('http://feeds.bbci.co.uk/news/rss.xml?edition=uk');
		$this->view->channelTitle=$channel->title;
		$this->view->channel=$channel;
		
		$article=new Articles();
		$article->setGuid('111111');
    	$article->setTitle('test');
    	$article->setDescription('test');
    	$article->setLink('test');
    	$date = new DateTime('now');
    	$article->setPubDate($date);
    	$this->em->persist($article);
      	$this->view->status.="<br/>saved<br/>";
		$this->em->flush(); 
    
    }
    
   public function saveAction() {
   	
      	$feedId = $this->getRequest()->getParam('uid');
      	$channel = new Zend_Feed_Rss('http://feeds.bbci.co.uk/news/rss.xml?edition=uk');
      	$this->view->status="Not found - the item is no longer available or the feed is down<br/>";
        foreach ($channel as $item){
            if(md5($item->guid())==$feedId){
            	$this->view->status="saving {$item->title()}<br/>";
            	$foundArticle=$this->em->getRepository('Articles')->findOneBy(array('guid'=>$item->guid()));
            	if(empty($foundArticle)){
	            	$article=new Articles();
	            	$article->setGuid($item->guid());
	            	$article->setTitle($item->title());
	            	$article->setDescription($item->description());
	            	$article->setLink($item->link());
	            	$date = new DateTime($item->pubDate());
	            	$article->setPubDate($date);
	            	$this->em->persist($article);
	              	$this->view->status.="<br/>saved<br/>";
            	}else{
            		$this->view->status.="<br/>Already Saved<br/>";
            		
            	}
            }
        }
   
   	 
      	 $this->em->flush();
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