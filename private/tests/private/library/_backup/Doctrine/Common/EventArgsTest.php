<?php

namespace Doctrine\Common;

require_once dirname(__FILE__) . '/../../../../../../library/_backup/Doctrine/Common/EventArgs.php';

/**
 * Test class for EventArgs.
 * Generated by PHPUnit on 2011-07-02 at 16:23:13.
 */
class EventArgsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var EventArgs
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
	$this->object = new EventArgs;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
	
    }

    /**
     * @todo Implement testGetEmptyInstance().
     */
    public function testGetEmptyInstance() {
	// Remove the following lines when you implement this test.
	$this->markTestIncomplete(
		'This test has not been implemented yet.'
	);
    }

}

?>