<?php
//namespace Wednesday\ZFDebug\Plugin;
/**
 * @see Doctrine
 */
use \Doctrine\DBAL\Logging\DebugStack;

/**
 * ZFDebug Zend Additions
 *
 * @category   ZFDebug
 * @package    ZFDebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2008-2009 ZF Debug Bar Team (http://code.google.com/p/zfdebug)
 * @license    http://code.google.com/p/zfdebug/wiki/License     New BSD License
 * @version    $Id: Doctrine.php 152 2010-06-18 15:38:32Z jamesahelly $
 */

/**
 * @category   ZFDebug
 * @package    ZFDebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2008-2009 ZF Debug Bar Team (http://code.google.com/p/zfdebug)
 * @license    http://code.google.com/p/zfdebug/wiki/License     New BSD License
 */
class Wednesday_ZFDebug_Plugin_Doctrine
    extends ZFDebug_Controller_Plugin_Debug_Plugin 
    implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
{

    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'doctrine';

    /**
     * @var array
     */
    protected $_db = array();
    
    protected $_explain = false;

    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Variables
     *
     * @param Zend_Db_Adapter_Abstract|array $adapters
     * @return void
     */
    public function __construct(array $options = array())
    {
		if (isset($options['adapter'])) {
			$this->_db['Doctrine'] = $options['adapter'];
		}
    }

    /**
     * Gets identifier for this plugin
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
    /**
     * Returns the base64 encoded icon
     *
     * @return string
     **/
    public function getIconData()
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC';
    }

    /**
     * Gets menu tab for the Debugbar
     *
     * @return string
     */
    public function getTab()
    {
        if (!$this->_db)
            return 'No doctrine';

		$config = $this->_db['Doctrine']->getConfiguration();
		$logger = $config->getSQLLogger();
		$totals = 0;
		foreach ($logger->queries as $queries) {
			$totals += $queries['executionMS'];
		}
		$html =  count($logger->queries).' in '. round(($totals * 1000) ,3) .' ms';
		
        return $html;
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        if (!$this->_db)
            return '';
            
        $html = '<h4>Database queries';
        //TODO Display other options that doctrine might have...
        $html .= '</h4>';
		$config = $this->_db['Doctrine']->getConfiguration();
		$logger = $config->getSQLLogger();
		$adapterInfo = array();
		foreach ($logger->queries as $queries) {
			$adapterInfo[] = "<li>".round(($queries['executionMS'] * 1000) ,3)."ms &nbsp; &nbsp; &nbsp; '". $queries['sql']."';</li>";
		}
		$html .=  "<ul>".implode("\n", $adapterInfo)."</ul>";

        return $html;
    }
}