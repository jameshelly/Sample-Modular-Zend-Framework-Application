<?php
/**
 * filename - The default error controller class
 *
 * @category	category_declaration
 * @package     project
 * @subpackage	project
 * @copyright	copyright_declaration
 * @license		license_declaration
 * @author      jamesh
 * @created     Mar 1, 2011
 * @version     $Id$
 */
 
class Default_Plugin_ThemedViews extends Zend_Layout_Controller_Plugin_Layout {
	
	private $config;
	private $view;
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
            $logger = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('logger');
            $config = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('config');
            
//            $modulePath = trim(Zend_Controller_Front::getInstance()->getModuleDirectory());
//            $modulePath .= "/configs/default.xml";
//            $theme = new Zend_Config_Xml($modulePath, 'site');

            $modulePath = trim(Zend_Controller_Front::getInstance()->getModuleDirectory());
            $modulePath .= "/configs/default.ini";
            $theme = new Zend_Config_Ini($modulePath, 'default');
            
            $themeXmlPath = WEB_PATH . $theme->theme->layout->path . 'config.ini';
            if(!file_exists($themeXmlPath)) {
                $theme = (object) array('theme' => $config->theme->fallback);
                $themeXmlPath = WEB_PATH . $theme->theme->layout->path . 'config.ini';
            }

            Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->set('theme',$theme);

            //Set layout files to look in Theme folder.
            $this->getLayout()->setLayoutPath( WEB_PATH . $theme->theme->layout->path );
            $this->getLayout()->setLayout($theme->theme->layout->skin);
            $this->getLayout()->setViewBasePath( WEB_PATH . $theme->theme->view->path );

//            //Detect Ajax Early.
//            if($request->isXmlHttpRequest()) {
//                    $this->getHelper('layout')->disableLayout();
//            }

            $this->config = new Zend_Config_Ini($themeXmlPath, 'global');
//            $this->config = new Zend_Config_Xml($themeXmlPath, 'global');
            $this->themeuri = "http://".BASE_URL.$theme->theme->view->path;
            $this->view = $this->getLayout()->getView();

            $this->view->theme = (object) array(
                'bodyClasses' => $theme->theme->name,
                'themePath' => "http://".BASE_URL.$theme->theme->view->path
            );

            $this->loadPageHead();
            $this->loadStyles();
            $this->loadBehaviours();
            $this->loadInlineScript();

            $logger->info(get_class($this).'::preDispatch[]');
    }
    
    /**
     * loadPageHead
     * Parse Theme Config XML.
     */
    public function loadPageHead() {
    	$description = "";
    	$keywords = "";
    	$charset = $this->view->encoding;
    	$date = new Zend_Date();
        /*
        foreach($this->config->metas->meta as $metadata) {
            if(isset($metadata->name)) {
                $metadatavalue = $metadata->param;
                if($metadata->param == 'default') {
                    switch($metadata->name){
                        case 'description':
                            $metadatavalue = $description;
                            break;
                        case 'keywords':
                            $metadatavalue = $keywords;
                            break;
                        case 'created':
                            $metadatavalue = $date->toString('yyyy-MM-dd');
                            break;
                        default:
                            $metadatavalue = '';
                            break;
                    }
                }
                $this->view->headMeta()->appendName($metadata->name, $metadatavalue);
            } else if(isset($metadata->use)) {
                switch($metadata->use) {
                    case 'default':
                    default:
                        $this->view->headMeta()->prependHttpEquiv('Content-Type', 'text/html; charset=' . $charset);
                        break;
                }
            }
        }
         */
        foreach($this->config->metas as $metaname => $metadata) {
            //echo $metaname . " " . $metadata->param . "<br />";
            //*
            if($metaname != 'use') {
                $this->view->headMeta()->appendName($metaname, $metadata->param);
            } else {
                $this->view->headMeta()->prependHttpEquiv('Content-Type', 'text/html; charset=' . $charset);
            }
            //*/
        }
    }
    
    /**
     * loadStyles
     * Parse Theme Config XML.
     */
    public function loadStyles() {
        /*
        foreach($this->config->styles->style as $style) {
            //die(print_r($style,true));
            if(!empty($style->name)) {
                $filter = "";
                if(!empty($style->filter)) {
                    $filter = $style->filter;
                }
                $cssUri = $this->themeuri.'css/'.$style->name.'.css';
                $this->view->headLink()->appendStylesheet($cssUri, $style->media, $filter);
            }
        }
        */
        foreach($this->config->styles as $style) {
            if(!empty($style->name)) {
                $filter = "";
                if(!empty($style->filter)) {
                    $filter = $style->filter;
                }
                $cssUri = $this->themeuri.'css/'.$style->name.'.css';
                $this->view->headLink()->appendStylesheet($cssUri, $style->media, $filter);
            }            
        }
    }
    
    /**
     * loadInlineScript
     * Parse Theme Config XML.
     */
    public function loadInlineScript() {
        $jqNoConflict = ZendX_JQuery_View_Helper_JQuery::getJQueryHandler();
        $navScript = <<<SOE
	{$jqNoConflict}(document).ready(function() {
        {$jqNoConflict}('.subnav').hide();
        {$jqNoConflict}('ul.mainnav li').bind(
                'click',
                function(e) {
                        e.stopImmediatePropagation();
                        var submenu = {$jqNoConflict}('.subnav', this);
                        if(submenu.length != 0){
                                e.preventDefault();
                                if({$jqNoConflict}('.subnav', this).data('toggleVisibility')=='visible') {
                                {$jqNoConflict}('.subnav', this).hide();
                                {$jqNoConflict}('.subnav', this).data('toggleVisibility','hidden');
                        } else {
                                {$jqNoConflict}('.subnav', this).show();
                                {$jqNoConflict}('.subnav', this).data('toggleVisibility','visible');
                        }
                }
                //!{$jqNoConflict}(this).parent('ul').hasClass('subnav')
            });
	});
SOE;
        $this->view->inlineScript()
                -> appendScript($navScript, 'text/javascript');
    	
    	/*
        foreach($this->config->styles->style as $style) {
                //die(print_r($style,true));
                if(!empty($style->name)) {
                        $filter = "";
                        if(!empty($style->filter)) {
                                $filter = $style->filter;
                        }
                        $cssUri = $this->themeuri.'css/'.$style->name.'.css';
                        $this->view->headLink()->appendStylesheet($cssUri, $style->media, $filter);
                }
        }
        */
    }
    
    /**
     * loadBehaviours
     * Parse Theme Config XML.
     */
    public function loadBehaviours() {
        
        //*
        foreach($this->config->behaviours as $behaviour) {
            #Do nothing for now...
            if(!empty($behaviour->name)) {
                switch($behaviour->name) {
                    case 'jquery':
                        $this->view->jQuery()
                            -> setVersion($behaviour->version)
                            -> enable();
                            ZendX_JQuery_View_Helper_JQuery::enableNoConflictMode();
                        if($behaviour->location != "CDN"){
                            $this->view->jQuery()
                                -> setLocalPath($this->themeuri.$behaviour->location.'/jquery-'.$behaviour->version.'.min.js');
                        }
                        if(isset($behaviour->extensions)){
                            foreach($behaviour->extensions as $extension) {
                                if(!empty($extension->name)) {
                                    switch($extension->name) {
                                        case 'jquery-ui':
                                            $this->view->jQuery()
                                                -> setUiVersion($extension->version)
                                                -> uiEnable();
                                            if($extension->location != "CDN") {
                                                $jsUrl = $extension->location.'/jquery-ui-'.$extension->version.'.'.$extension->theme.'.min.js';
                                                $jsUrl = (substr($extension->location, 0,1) == '/')?$jsUrl:$this->themeuri.$jsUrl;
                                                $cssUrl = (substr($extension->location, 0,1) == '/')?'/assets/css/'.$extension->theme.'/jquery-ui-'.$extension->version.'.custom.css':$this->themeuri.'css/jquery-ui-'.$extension->version.'.'.$extension->theme.'.css';
                                                $this->view->jQuery()
                                                    -> setUiLocalPath($jsUrl)
                                                    -> addStylesheet($cssUrl);
                                            }
                                            $this->view->theme->bodyClasses .= ' '.$extension->theme.'';
                                            break;
                                        default:
                                            $this->view->headScript()->appendFile($this->themeuri.$extension->location.$extension->name.'-'.$extension->version.'.min.js', 'text/javascript');
                                            break;
                                    }
                                }
                            }
                        }
                        break;
                    case 'aloha':
                        $this->view->headScript()
                            -> appendFile($this->themeuri.$behaviour->location.'/'.$behaviour->name.'-nodeps.js', 'text/javascript');
                        if(isset($behaviour->extensions)){
                            foreach($behaviour->extensions->extension as $extension) {
                                if(!empty($extension->name)) {
                                    $this->view->headScript()-> appendFile($this->themeuri.$behaviour->location.'/'.$extension->location.'/'.$extension->name.'.js', 'text/javascript');
                                }
                            }
                        }
                        break;
                    default:
                        $this->view->headScript()
                            -> appendFile($this->themeuri.$behaviour->location.$behaviour->name.'-'.$behaviour->version.'.min.js', 'text/javascript');
                        break;
                }
            }
        }
        /*
        foreach($this->config->behaviours->behaviour as $behaviour) {
            #Do nothing for now...
            if(!empty($behaviour->name)) {
                switch($behaviour->name) {
                    case 'jquery':
                        $this->view->jQuery()
                            -> setVersion($behaviour->version)
                            -> enable();
                            ZendX_JQuery_View_Helper_JQuery::enableNoConflictMode();
                        if($behaviour->location != "CDN"){
                            $this->view->jQuery()
                                -> setLocalPath($this->themeuri.$behaviour->location.'/jquery-'.$behaviour->version.'.min.js');
                        }
                        if(isset($behaviour->extensions)){
                            foreach($behaviour->extensions->extension as $extension) {
                                if(!empty($extension->name)) {
                                    switch($extension->name) {
                                        case 'jquery-ui':
                                            $this->view->jQuery()
                                                -> setUiVersion($extension->version)
                                                -> uiEnable();
                                            if($extension->location != "CDN") {
                                                $jsUrl = $extension->location.'/jquery-ui-'.$extension->version.'.'.$extension->theme.'.min.js';
                                                $jsUrl = (substr($extension->location, 0,1) == '/')?$jsUrl:$this->themeuri.$jsUrl;
                                                $cssUrl = (substr($extension->location, 0,1) == '/')?'/assets/css/'.$extension->theme.'/jquery-ui-'.$extension->version.'.custom.css':$this->themeuri.'css/jquery-ui-'.$extension->version.'.'.$extension->theme.'.css';
                                                $this->view->jQuery()
                                                    -> setUiLocalPath($jsUrl)
                                                    -> addStylesheet($cssUrl);
                                            }
                                            $this->view->theme->bodyClasses .= ' '.$extension->theme.'';
                                            break;
                                        default:
                                            $this->view->headScript()->appendFile($this->themeuri.$extension->location.$extension->name.'-'.$extension->version.'.min.js', 'text/javascript');
                                            break;
                                    }
                                }
                            }
                        }
                        break;
                    case 'aloha':
                        $this->view->headScript()
                            -> appendFile($this->themeuri.$behaviour->location.'/'.$behaviour->name.'-nodeps.js', 'text/javascript');
                        if(isset($behaviour->extensions)){
                            foreach($behaviour->extensions->extension as $extension) {
                                if(!empty($extension->name)) {
                                    $this->view->headScript()-> appendFile($this->themeuri.$behaviour->location.'/'.$extension->location.'/'.$extension->name.'.js', 'text/javascript');
                                }
                            }
                        }
                        break;
                    default:
                        $this->view->headScript()
                            -> appendFile($this->themeuri.$behaviour->location.$behaviour->name.'-'.$behaviour->version.'.min.js', 'text/javascript');
                        break;
                }
            }
        }        
        //*/
    }

}