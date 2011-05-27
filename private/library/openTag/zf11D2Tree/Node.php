<?php

namespace Opentag\Tree;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Tree Node
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Opentag.Tree
 * @subpackage Node
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Node
{
    // use now annotations instead of predifined methods, this interface is not necessary
    
    /**
     * @Opentag:TreeLeft
     * to mark the field as "tree left" use property annotation @Opentag:TreeLeft
     * it will use this field to store tree left value
     */
    
    /**
     * @Opentag:TreeRight
     * to mark the field as "tree right" use property annotation @Opentag:TreeRight
     * it will use this field to store tree right value
     */
    
    /**
     * @Opentag:TreeParent
     * in every tree there should be link to parent. To identify a relation
     * as parent relation to child use @Tree:Ancestor annotation on the related property
     */

    /**
     * @Opentag:TreeLevel
     * level of node.
     */
}