<?php 
namespace Opentag\Restable;

/**
 * This interface is not necessary but can be implemented for
 * Domain Objects which in some cases needs to be identified as
 * Loggable
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Opentag.Restable
 * @subpackage Restable
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Restable
{
    // this interface is not necessary to implement
    
    /**
     * @Opentag:Restable
     * to mark the class as loggable use class annotation @Opentag:Restable
     * this object will contain now a history
     * available options:
     *         logEntryClass="My\LogEntryObject" (optional) defaultly will use internal object class
     * example:
     * 
     * @Opentag:Restable(logEntryClass="My\LogEntryObject")
     * class MyEntity
     */
     
    /**
     * NestedSet strategy
     */
    const NESTED = 'nested';

    /**
     * Closure strategy
     */
    const CLOSURE = 'closure';

    /**
     * Get the name of strategy
     *
     * @return string
     */
    function getName();

    /**
     * Initialize strategy with tree listener
     *
     * @param TreeListener $listener
     * @return void
     */
    function __construct(TreeListener $listener);

    /**
     * Operations after metadata is loaded
     *
     * @param object $om
     * @param ClassMetadata $meta
     */
    function processMetadataLoad($om, $meta);

    /**
     * Operations on tree node insertion
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processScheduledInsertion($om, $object);

    /**
     * Operations on tree node updates
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processScheduledUpdate($om, $object);

    /**
     * Operations on tree node delete
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processScheduledDelete($om, $object);

    /**
     * Operations on tree node removal
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processPreRemove($om, $object);

    /**
     * Operations on tree node persist
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processPrePersist($om, $object);

    /**
     * Operations on tree node insertions
     *
     * @param object $om - object manager
     * @param object $object - node
     * @return void
     */
    function processPostPersist($om, $object);

    /**
     * Operations on the end of flush process
     *
     * @param object $om - object manager
     * @return void
     */
    function onFlushEnd($om);
}