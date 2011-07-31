<?php
namespace Opentag\Auth\Adapter;

use Opentag,
    \Doctrine\ORM\EntityManager,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Wednesday
 * @package    Zend_Auth
 * @subpackage Adapter
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Doctrine.php 23483 2011-02-01 09:40:01Z jameshelly $
 */

/**
 * @see Zend_Auth_Adapter_Interface
require_once 'Zend/Auth/Adapter/Interface.php';
 */

/**
 * @see Zend_Auth_Result
require_once 'Zend/Auth/Result.php';
 */

/**
 * @category   Wednesday
 * @package    Zend_Auth
 * @subpackage Adapter
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Doctrine implements \Zend_Auth_Adapter_Interface {

    /**
     * $entityManager - Entity Manager
     *
     * @var string
     */
    protected $entityManager = null;

    /**
     * $entityName - Identity value
     *
     * @var string
     */
    protected $entityName = null;

    /**
     * $identityGetter - Identity value
     *
     * @var string
     */
    protected $identityGetter = null;

    /**
     * $credentialGetter - Identity value
     *
     * @var string
     */
    protected $credentialGetter = null;

    /**
     * $credentialChecker - Identity value
     *
     * @var string
     */
    protected $credentialChecker = null;

	/**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

    /**
     * __construct() - Sets configuration options
     *
     * @param  \Doctrine\ORM\EntityManager $DoctrineEM If null, default database adapter assumed
     * @param  string                   $tableName
     * @param  string                   $entityName
     * @param  string                   $credentialGetter
     * @param  string                   $credentialCheck
     * @return void
     */
    public function __construct(
    	EntityManager $DoctrineEM = null,
    	$entityName = null,
    	$identityGetter = null,
    	$credentialGetter = null,
    	$credentialCheck = null
    )
    {
    	$this->entityManager = $DoctrineEM;
        if (null !== $entityName) {
            $this->entityName = $entityName;
        }
        if (null !== $identityGetter) {
            $this->identityGetter = $identityGetter;
        }
        if (null !== $credentialGetter) {
            $this->credentialGetter = $credentialGetter;
        }
        if (null !== $credentialCheck) {
           $this->credentialChecker = $credentialCheck;
        }
    }

	/**
	 * _authenticateSetup()
	 *
	 */
	protected function _authenticateSetup() {
		return;
	}

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
    	$this->_authenticateSetup();

        $userRepo = $this->entityManager->getRepository($this->entityName);
        $user = $userRepo->findByUsername($this->_identity);
		if(!empty($user[0]) && (count($user) == 1) ) {
			$check = $this->credentialChecker;
			error_log($user[0]->$check($this->_credential).' - '.$this->_credential);
			if($user[0]->$check($this->_credential)){
				$authResult = new Zend_Auth_Result(
	            	Zend_Auth_Result::SUCCESS,
	            	$this->_identity,
	            	array('Authentication successful.')
	            );
	            return $authResult;
			} else {
			    $authResult = new Zend_Auth_Result(
			       Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
			       $this->_identity,
			       array('Authentication fail.')
			    );
			}
		} if(count($user)>1) {
			$authResult = new Zend_Auth_Result(
	           	Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
	           	$this->_identity,
	           	array('Authentication fail.')
			);
		} else {
			$authResult = new Zend_Auth_Result(
	           	Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
	           	$this->_identity,
	           	array('Authentication fail.')
			);
		}
        return $authResult;
    }

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setIdentity($identity)
    {
        $this->_identity = $identity;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

}