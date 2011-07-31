<?php 
namespace Wednesday\Restable;

/**
 * This interface is not necessary but can be implemented for
 * Domain Objects which in some cases needs to be identified as Restable
 * 
 * @author James A Helly <james@wednesday-london.com>
 * @package Wednesday.Restable
 * @subpackage Restable
 * @link http://cms.wednesday-london.com/
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Entity
{
    
    /**
     * @Wednesday:Restable:Entity
     * class Entity
     */

/**
     * createAction
     * 
     * @param array $regVars
     * @return boolean $retval
     */
    public function createAction($reqVars);

    /**
     * readAction
     *
     * @param array $regVars
     * @return boolean $retval
     */
    public function readAction($reqVars);

    /**
     * updateAction
     *
     * @param array $regVars
     * @return boolean $retval
     */
    public function updateAction($reqVars);

    /**
     * deleteAction
     *
     * @param array $regVars
     * @return boolean $retval
     */
    public function deleteAction($reqVars);

    
    /**
     * toJsonObject
     * 
     * return Object
     */
    public function toJsonObject();
}