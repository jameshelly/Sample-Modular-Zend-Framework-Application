<?php

namespace openTag\Timestampable;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Timestampable
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag.Timestampable
 * @subpackage Timestampable
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Timestampable
{
    // timestampable expects annotations on properties
    
    /**
     * @openTag:Timestampable(on="create")
     * dates which should be updated on insert only
     */
    
    /**
     * @openTag:Timestampable(on="update")
     * dates which should be updated on update and insert
     */
    
    /**
     * @openTag:Timestampable(on="change", field="field", value="value")
     * dates which should be updated on changed "property" 
     * value and become equal to given "value"
     */
    
    /**
     * example
     * 
     * @openTag:Timestampable(on="create")
     * @Column(type="date")
     * $created
     */
}