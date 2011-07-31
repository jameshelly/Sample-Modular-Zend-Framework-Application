<?php

namespace Wednesday;

/**
 * Common package exception interface to allow
 * users of caching only this package specific
 * exceptions thrown
 * 
 * @author James A Helly <james@wednesday-london.com>
 * @package Wednesday
 * @subpackage Template
 * @link http://www.wednesday-london.com
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Template
{
    /**
     * Following best practices for PHP5.3 package exceptions.
     * All exceptions thrown in this package will have to implement this interface
     * 
     * @link http://wiki.php.net/pear/rfc/pear2_exception_policy
     */
    public $baseurl;
    
    public function enableView($view) {
        return false;//new \Zend_View_Helper_Partial();//Wednesday_Template::init();
    }
}