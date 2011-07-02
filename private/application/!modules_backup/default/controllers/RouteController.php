<?php

/**
 * RouteController - The default controller
 *
 * @author mrhelly
 * @version
 */
class RouteController extends Wednesday_Controller_Action {
    
    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
    	#Get params
    	$format = $this->getRequest()->getParam('format');
    	$route = $this->getRequest()->getParam('route');
        #Push Context Switching.
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('index', array('html', 'xml', 'json'))->initContext();
        #Set message
        $this->view->message = $route;
        #TODO Parse route.
        $parts = explode('/', $route);
        $content = "<pre>".var_export($parts,true)."</pre>";
        
        $test = $this->getPageFromRoute($route);
        
        $content .= "<hr /><pre>".$test->getName().":".$test->getRoute()."</pre>";
        #Set content
        $this->view->content = $content;
    }
    
    protected function getPageFromRoute($route) {
		$parts = explode('/', $route);
    	$repo = $this->em->getRepository('Routes');
    	#Start at the beginning.
    	$baseroute = $repo->findOneByRoute('/');
    	$selectedroute = $baseroute;
    	$kids = $repo->children($baseroute, true);
    	/*
    	while($kids) {
    		foreach($kids as $kid) {
	    		if($part == $kid->getRoute()) {
	    			$selectedroute = $kid;
    				$kids = $repo->children($kid, true);
    				break 2;
   		 		}
   		 	}
    	}
    	//*/
    	return $selectedroute;
    }
    
/*
    protected function checkPage($part) {
    	//$this->getPage($route);
    	$kids = $repo->children($route, true);
		foreach($kids as $kid) {
			if($part == $kid->getRoute()) {
				$selectedroute = $kid;
				//$kids = $repo->children($kid, true);
				break;
	 		}
	 	}
	 	return $selectedroute;
    }
*/

    
}

?>
