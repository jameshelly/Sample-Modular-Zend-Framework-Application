<?php

namespace Doctrine\DBAL;

require_once dirname(__FILE__) . '/../../../../../../library/_backup/Doctrine/DBAL/LockMode.php';

/**
 * Test class for LockMode.
 * Generated by PHPUnit on 2011-07-02 at 16:23:14.
 */
class LockModeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LockMode
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
	$this->object = new LockMode;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
	
    }

}

?>