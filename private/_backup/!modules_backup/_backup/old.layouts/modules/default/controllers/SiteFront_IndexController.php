<?php

/**
 * Sitefront_IndexController - The default controller
 *
 * @author
 * @version
 */

class Sitefront_IndexController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $em = $bootstrap->getContainer()->get('entity.manager');
        $display = "";
        $users = $em->find('Users', 2);
        $display .= "User : ".$users->getUsername()." <br />";
        $role = $users->getRole();
        $adapter = new Wednesday_Auth_Adapter_Doctrine(
            $em,
            'Users',
            'getUsername',
            'getPassword',
            "checkPassword"
        );
        $em->flush();
        $display = get_class($this)." Index";
        $this->view->message = $display;

        $this->log->info(get_class($this).'::indexAction()');
    }

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function defaultAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $em = $bootstrap->getContainer()->get('entity.manager');
        $display = "";

        $theme = $bootstrap->getContainer()->get('theme');
        //$feedUrl = 'http://wednesday-london.com/feed/';
        $feedUrl = 'http://api.flickr.com/services/feeds/photos_public.gne?id=39825633@N00&lang=en-us&format=rss_200';
        $feed = Zend_Feed::import($feedUrl);
        foreach ($feed->items as $item) {
            $description = ($item->description[0] == "")?$item->description:$item->description[0];
            $title = ($item->title[0] == "")?$item->title:$item->title[0];
            $this->view->accordionPane("newstab", $description, array('title' => $title));
        }
        $display .= get_class($this)." Index<br />\n";
        $display .= $this->view->accordionContainer("newstab", array(), array('class' => $theme->theme->name));
	$this->view->layout()->aside .= "<p>Mofo</p>";
    }

}
