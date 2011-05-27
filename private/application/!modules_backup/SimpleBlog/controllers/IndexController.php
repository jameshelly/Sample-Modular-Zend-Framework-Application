<?php

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class SimpleBlog_IndexController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
        
        $bootstrap = $this->getInvokeArg('bootstrap');
        
//        $em = $bootstrap->getContainer()->get('entity.manager');
//        $routes = $em->getRepository('Articles')->findAll();
//        $display .= "<ul id=\"primaryNav\" class=\"col4\">";
//        foreach($routes as $key => $route) {
//                $display .= "<li><a href=\"".$route->getRoute()."\">".$route->getName()."</a></li>";
//        }
//        $display .= "</ul>";
        
        $display = "";
        $display .= "<h1>Blog</h1>";
        $display .= "<h2>".get_class($this)."</h2>";
        
        $theme = $bootstrap->getContainer()->get('theme');
        //$feedUrl = 'http://wednesday-london.com/feed/';
//        $feedUrl = 'http://api.flickr.com/services/feeds/photos_public.gne?id=39825633@N00&lang=en-us&format=rss_200';
//        $feed = Zend_Feed::import($feedUrl);
//        foreach ($feed->items as $item) {
//            $description = ($item->description[0] == "")?$item->description:$item->description[0];
//            $title = ($item->title[0] == "")?$item->title:$item->title[0];
//            $this->view->accordionPane("newstab", $description, array('title' => $title));
//        }
        $display .= get_class($this)." Index<br />\n";
        $display .= $this->view->accordionContainer("newstab", array(), array('class' => $theme->theme->name));

        $this->view->message = $display;
        $this->log->info(get_class($this).'::indexAction()');
    }

}
