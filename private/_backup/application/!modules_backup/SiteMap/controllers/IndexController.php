<?php

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class SiteMap_IndexController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
        
        $bootstrap = $this->getInvokeArg('bootstrap');
        $em = $bootstrap->getContainer()->get('entity.manager');
        $display = "";
        $display .= "<div class='sitemap'>";
        $display .= "<h1>Routes</h1>";
        $display .= "<h2>".get_class($this)."</h2>";
        $routes = $em->getRepository('Routes')->findAll();
        $display .= "<ul id=\"primaryNav\" class=\"col4\">";
        foreach($routes as $key => $route) {
                $display .= "<li><a href=\"".$route->getRoute()."\">".$route->getName()."</a></li>";
        }
        /*
        $display .= '<li><a href="/about">About Us</a>
				<ul>
					<li><a href="/history">Our History</a></li>
					<li><a href="/mission">Mission Statement</a></li>
					<li><a href="/principals">Principals</a></li>
				</ul>
			</li>
			<li><a href="/services">Services</a>
				<ul>
					<li><a href="/services/design">Graphic Design</a></li>
					<li><a href="/services/development">Web Development</a></li>
					<li><a href="/services/marketing">Internet Marketing</a>
						<ul>
							<li><a href="/social-media">Social Media</a></li>
							<li><a href="/optimization">Search Optimization</a></li>
							<li><a href="/adwords">Google AdWords</a></li>
						</ul>
					</li>
					<li><a href="/services/copywriting">Copywriting</a></li>
					<li><a href="/services/photography">Photography</a></li>
				</ul>
			</li>
			<li><a href="/projects">Projects</a>
				<ul>
					<li><a href="/projects/finance">Finance</a></li>
					<li><a href="/projects/medical">Medical</a></li>
					<li><a href="/projects/education">Education</a></li>
					<li><a href="/projects/professional">Professional</a></li>
					<li><a href="/projects/industrial">Industrial</a></li>
					<li><a href="/projects/commercial">Commercial</a></li>
					<li><a href="/projects/energy">Energy</a></li>
				</ul>
			</li>
			<li><a href="/contact">Contact</a>
				<ul>
					<li><a href="/contact/offices">Offices</a>
						<ul>
							<li><a href="contact/map">Map</a></li>
							<li><a href="contact/form">Contact Form</a></li>
						</ul>
					</li>
				</ul>
			</li>';
         */
        $display .= "</ul>";
        $display .= "</div>";
        $this->view->message = $display;
        $this->log->info(get_class($this).'::indexAction()');
    }

}
