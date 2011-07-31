<?php
//namespace Wednesday\Controller;

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Collections\ArrayCollection, 
    Application\Entities\Collections,
    Application\Entities\Addresses,
    Application\Entities\GeoLocations,
    Application\Entities\Telephones,
    Application\Entities\Organizations,
    Application\Entities\hCards,
    Wednesday\Restable\RestListener,
    Gedmo\Tree\TreeListener;

require_once 'Zend/Controller/Action.php';

class Wednesday_Controller_Action extends \Zend_Controller_Action
{
    
    /**
     *
     * @var boolean 
     */
    protected $initialised;

    /**
     *
     * Zend_Auth object
     * @var Zend_Auth
     */
    protected $auth;

    /**
     *
     * Auto Loaded acl object to filter program flow.
     * @var Wednesday_Application_Resource_AccessControlList
     */
    protected $acl;

    /**
     * Doctrine\ORM\EntityManager object wrapping the entity environment
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * Access to Zend_Log.
     * @var Zend_Log
     */
    public $log;

    /**
     * Store bookstrap
     * @see Controller/Zend_Controller_Action::preDispatch()
     */
    public function preDispatch() {
    	#init live plugins.
    
    	//$bootstrap = $this->getInvokeArg('bootstrap');
	//$bootstrap->getContainer()->get('acl.manager')->getAclResource($this->getRequest());
        //http://sandbox.api.yoox.biz/Geo.API/1.0/site/MONCLER/160.97.4.26.json
    }

    /**
     * This action handles
     *    - Default Action Initialisation
     */
    public function init() {
        if($this->initialised) {
            return;
        }
    	#Get bootstrap object.
        $bootstrap = $this->getInvokeArg('bootstrap');
        #Get Logger
        if ($this->log = $this->getLog()) {
            $this->log->info(get_class($this).'::ini()');
        } else {
            $this->log = $bootstrap->getContainer()->get('logger');
        }
        $init = ($this->initialised)?'true':'false';
        $this->log->info(get_class($this).'::init( '.$init.' )');
        

    	#Get Doctrine Entity Manager
        $this->em = $bootstrap->getContainer()->get('entity.manager');
        
        #Get Acl Object
        //$this->acl = $bootstrap->getContainer()->get('acl.manager');
        //$this->acl->getAclResource($this->getRequest());
        
        #Get Zend Auth.
        $this->auth = Zend_Auth::getInstance();
	
        #Set locale defaults
        $locale = $this->view->placeholder('locale');
        $encoding = 'UTF-8';
        
        #Make request available to view
	$request = $this->getRequest();
	$this->view->request = $request;
	
	if($this->view->initialised === true) {
	    $this->log->info(get_class($this).'::init( Hmmm )');
	    return;
	}
	
        #Setup styles & output variables.
        $this->view->headTitle()->setSeparator(' / ');
        $this->view->headTitle('Moncler')
		    ->headTitle($request->getModuleName())
		    ->headTitle($request->getControllerName())
		    ->headTitle($request->getActionName());
        $this->view->doctype('HTML5');
        $this->view->headMeta()
            ->setCharset($encoding)
	    ->appendHttpEquiv
	    (
		'Content-Type',
		'text/html; charset='.strtoupper($encoding)
	    )
	    ->appendHttpEquiv
	    (
		'Content-Language',
		$locale
	    )
	    ->appendHttpEquiv
	    (
		'X-UA-Compatible',
		'IE=edge,chrome=1'
	    )
	    ->appendHttpEquiv
	    (
		'og:title',
		'Moncler'
	    )
	    ->appendHttpEquiv
	    (
		'og:type',
		''
	    )
	    ->appendHttpEquiv
	    (
		'og:url',
		$this->view->serverUrl($this->view->url())
	    )
	    ->appendHttpEquiv
	    (
		'og:image',
		$this->view->serverUrl('/assets/img/branding/MONCLER_logo-screen.png')
	    )
	    ->appendHttpEquiv
	    (
		'og:site_name',
		'Moncler'
	    )
	    ->appendHttpEquiv
	    (
		'og:admins',
		'USER_ID'
	    )
	    ->appendHttpEquiv
	    (
		'og:description',
		''
	    );

        $this->view
        ->headLink
        (
                array
                (
                        'rel' => 'home',
                        'href' => $this->view->baseUrl('/')
                )
		)
		->headLink
        (   
                array
                (
                        'rel' => 'favicon',
                        'href' => $this->view->theme->themePath.'img/branding/favicon.ico'
                )
		)
		->headLink
        (   
                array
                (
                        'rel' => 'shortcut icon',
                        'href' => $this->view->theme->themePath.'img/branding/favicon.ico'
                )
		)
		->headLink
        (   
                array
                (
                        'rel' => 'apple-touch-icon',
                        'href' => $this->view->theme->themePath.'img/branding/apple-touch-icon.png'
                )
		)
		->headLink
        (   
                array
                (
                        'rel' => 'profile',
                        'href' => 'http://microformats.org/profile/hatom'
                )
		)
		->headLink
        (   
                array
                (
                        'rel' => 'profile',
                        'href' => 'http://microformats.org/profile/hcard'
                )
        );

        $this->view->inlineScript()->appendFile('http://connect.facebook.net/en_US/all.js#xfbml=1');
        $this->view->inlineScript()->appendFile('http://platform.twitter.com/widgets.js');

        $this->view->inlineScript()->captureStart();
        ?>
			_gaq.push(['_setAccount', 'UA-11231708-2']);
		<?php
		$this->view->inlineScript()->captureEnd();

	#Navigation
	$this->container = new Zend_Navigation();

	$navigationItems = array
	(
		array
		(
		    'label'			=>	$this->view->placeholder('translate')->translate->_('Shop Mens'),
		    'type'			=> 'uri',
		    'uri'			=>	$this->view->commerceSite('/moncler/home/realgender/man/tskay/1B23EDB7/gender/U/agerange/Adult'),
		    'accesskey'		=>	'2'
		),
		array
		(
		    'label'			=>	$this->view->placeholder('translate')->translate->_('Shop Womens'),
		    'type'			=> 'uri',
		    'uri'			=>	$this->view->commerceSite('/moncler/home/realgender/woman/tskay/1B23EDB7/gender/D/agerange/Adult'),
		    'accesskey'		=>	'3'
		),
		array
		(
		    'label'			=> $this->view->placeholder('translate')->translate->_('Collections'),
		    'type'			=> 'mvc',
		    'accesskey'			=> '4',
		    'action'			=> 'routed',
		    'controller'		=> 'index',
		    'module'			=> 'collections',
		    'route'			=> 'collections',
		    'pages'			=> array(
									array(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-mens'),
										'action'		=> 'routed',
										'controller'		=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=>	array
															(
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Mens'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-mens'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Mens'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-mens', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Womens'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-womens'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Womens'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-womens', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Mens Accessories'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-mens-accessories'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Mens Accessories'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-mens-accessories', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Womens Accessories'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-womens-accessories'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Womens Accessories'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-womens-accessories', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Kids'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-kids'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Kids'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-kids', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																)
															)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler V'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-v'),
										'action'		=> 'routed',
										'controller'		=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=> array(
														    array
															(
																'label'			=> $this->view->placeholder('translate')->translate->_('Gallery'),
																'type'			=> 'mvc',
																'params'		=> array('collection' => 'moncler-v', 'gallery' => 'gallery'),
																'action'		=> 'routed',
																'controller'		=> 'index',
																'module'		=> 'collections',
																'route'			=> 'collections',
																'class'			=> 'screen-offset'
															)
										)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler S'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-s'),
										'action'		=> 'routed',
										'controller'		=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=> array(
														    array
															(
																'label'			=> $this->view->placeholder('translate')->translate->_('Gallery'),
																'type'			=> 'mvc',
																'params'		=> array('collection' => 'moncler-s', 'gallery' => 'gallery'),
																'action'		=> 'routed',
																'controller'		=> 'index',
																'module'		=> 'collections',
																'route'			=> 'collections',
																'class'			=> 'screen-offset'
															)
										)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Grenoble'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-grenoble-mens'),
										'action'		=> 'routed',
										'controller'	=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=>	array
															(
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Mens'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-grenoble-mens'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Mens'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-grenoble-mens', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Womens'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'moncler-grenoble-womens'),
																	'action'		=> 'routed',
																	'controller'	=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Womens'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'moncler-grenoble-womens', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																)
															)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Bleu'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-gamme-bleu'),
										'action'		=> 'routed',
										'controller'	=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=> array(
														    array
															(
																'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Bleu'),
																'type'			=> 'mvc',
																'params'		=> array('collection' => 'moncler-gamme-bleu', 'gallery' => 'gallery'),
																'action'		=> 'routed',
																'controller'		=> 'index',
																'module'		=> 'collections',
																'route'			=> 'collections',
																'class'			=> 'screen-offset'
															)
										)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Rouge'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'moncler-gamme-rouge'),
										'action'		=> 'routed',
										'controller'	=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=> array(
														    array
															(
																'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Rouge'),
																'type'			=> 'mvc',
																'params'		=> array('collection' => 'moncler-gamme-rouge', 'gallery' => 'gallery'),
																'action'		=> 'routed',
																'controller'		=> 'index',
																'module'		=> 'collections',
																'route'			=> 'collections',
																'class'			=> 'screen-offset'
															)
										)
									),
									array
									(
										'label'			=> $this->view->placeholder('translate')->translate->_('Campaigns'),
										'type'			=> 'mvc',
										'params'		=> array('collection' => 'campaigns-moncler'),
										'action'		=> 'routed',
										'controller'		=> 'index',
										'module'		=> 'collections',
										'route'			=> 'collections',
										'pages'			=>	array
															(
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Moncler'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'campaigns-moncler'),
																	'action'		=> 'routed',
																	'controller'		=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Moncler'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'campaigns-moncler', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																),
																array
																(
																	'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Rouge'),
																	'type'			=> 'mvc',
																	'params'		=> array('collection' => 'campaigns-gamme-rouge'),
																	'action'		=> 'routed',
																	'controller'		=> 'index',
																	'module'		=> 'collections',
																	'route'			=> 'collections',
																	'pages'			=> array(
																					    array
																						(
																							'label'			=> $this->view->placeholder('translate')->translate->_('Moncler Gamme Rouge'),
																							'type'			=> 'mvc',
																							'params'		=> array('collection' => 'campaigns-gamme-rouge', 'gallery' => 'gallery'),
																							'action'		=> 'routed',
																							'controller'		=> 'index',
																							'module'		=> 'collections',
																							'route'			=> 'collections'
																						)
																	)
																)
															)
									)
								)
		),
		array
		(
		    'label'			=>	$this->view->placeholder('translate')->translate->_('News'),
		    'type'			=>	'mvc',
		    'accesskey'			=>	'5',
		    'action'			=> 'index',
		    'controller'	        => 'index',
		    'module'			=> 'news',
		    'route'			=> 'news',
		    'pages'			=>	array
								(
									array
									(
										'type'			=>	'mvc',
										'action'		=> 'gallery',
										'controller'		=> 'index',
										'module'		=> 'news',
										'route'			=> 'news',
										'pages'			=>	array
															(
																array
																(
																	'type'			=>	'Wednesday_Navigation_Page',
																	'label'			=>	$this->view->placeholder('translate')->translate->_('Back to all News'),
																	'uri'			=>	$this->view->baseUrl('/news/'),
																	'action'		=>	'latest',
																	'controller'	=>	'index',
																	'module'		=>	'news',
																	'route'			=>	'news',
																	'class'			=>	'icon back-arrow inactive'
																)
															)
									)
								)
		),
		array
		(
		    'label'			=>	$this->view->placeholder('translate')->translate->_('Stores'),
		    'type'			=>	'mvc',
		    'accesskey'			=>	'6',
		    'action'			=>	'index',
		    'controller'		=>	'index',
		    'module'			=>	'stores',
		    'route'			=>	'stores'
		)
	);

	//die('request: ' . print_r($this->getRequest(), true) . "\r\n" . 'route: ' . Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName());
	$this->container->addPages($navigationItems);
	$this->view->navigation($this->container);

        #Setup default view settings.
        $charset = 'utf-8';
        $date = new Zend_Date();
        $keywords = '';
        $description = '';
        $config = $bootstrap->getContainer()->get('config');
        
        #Navigation TODO Add dynamic generation. +acl filtering.
        //$this->view->navigation = $this->setNavigationContainer();
        //$this->view->menu = $this->renderNavigation();

        #Init Context Switching
    	$this->_helper->contextSwitch()->setContext(
             'html', array(
                 'suffix'    => 'html',
                 'headers'   => array(
                     'Content-Type' => 'text/html; Charset=UTF-8',
                 ),
             ),
             'xml', array(
                 'suffix'    => 'xml',
                 'headers'   => array(
                     'Content-Type' => 'text/xml; Charset=UTF-8',
                 ),
             ),
             'json', array(
                 'suffix'    => 'json',
                 'headers'   => array(
                     'Content-Type' => 'application/json; Charset=UTF-8',
                 ),
             )
         )->setAutoJsonSerialization(false)->initContext();
	
        #Footer 
	$vcard = array
	(
	    'organisation'		=> 'Moncler',
	    'street_address'		=> 'Biscuit Building',
	    'extended_address'		=> '10 Redchurch Street',
	    'locality'			=> 'Shorditch',
	    'region'			=> 'London',
	    'postal_code'		=> 'E2 7DD',
	    'country'			=> 'United Kingdom',
	    'telephone_work_tel'	=> '+ 44 20 7033 7731',
	    'telephone_work_fax'	=> null,
	    'email'			=> 'info@moncler.com'
	);

	$this->view->placeholder('footer-vcard')->exchangeArray($vcard);
	
        $topmenu = array(
            array('accesskey'=>'i','uri'=>'/auth/login','classes'=>'classes','label'=>'Login'),
            array('accesskey'=>'o','uri'=>'/auth/logout','classes'=>'lynx action-update','label'=>'Logout'),
         );
        $this->view->placeholder('topmenu')->set($topmenu);
        $leftmenu = array(
            array('accesskey'=>'1','uri'=>'/admin','classes'=>'classes','label'=>'Admin'),
            array('accesskey'=>'2','uri'=>'/admin/news','classes'=>'classes','label'=>'News'),
            array('accesskey'=>'3','uri'=>'/admin/gallery','classes'=>'classes','label'=>'Gallery'),
            array('accesskey'=>'4','uri'=>'/admin/categories','classes'=>'classes','label'=>'Categories'),
            array('accesskey'=>'5','uri'=>'/admin/tags','classes'=>'classes','label'=>'Tags'),
            array('accesskey'=>'6','uri'=>'/admin/assets','classes'=>'classes','label'=>'Assets'),
         );
        $this->view->placeholder('leftmenu')->set($leftmenu);

        $currenturi = $this->getRequest()->getRequestUri();
        $this->view->placeholder('currenturi')->set($currenturi);
        
        $this->view->initialised = $this->initialised = true;
	$init = ($this->initialised)?'true':'false';
        $this->log->info(get_class($this).'::init('.$init.')');
    }
    
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    public function getUniqid(){
            return uniqid().dechex(rand(65536,1048574));	
    }
}
