<?php

//namespace Wednesday;

/**
 * Allow override of MVC navigation href generation
 * 
 * @author James A Helly <james@wednesday-london.com>
 * @package Wednesday
 * @subpackage Template
 * @link http://www.wednesday-london.com
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Wednesday_Navigation_Page extends Zend_Navigation_Page_Mvc
{
    public function getHref()
    {
    	return $this->uri;
    }
}