<?php

namespace openTag\Tree;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Tree Node
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package openTag.Tree
 * @subpackage Node
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Node
{
    // use now annotations instead of predifined methods, this interface is not necessary
    
    /**
     * @openTag:TreeLeft
     * to mark the field as "tree left" use property annotation @openTag:TreeLeft
     * it will use this field to store tree left value
     */
    
    /**
     * @openTag:TreeRight
     * to mark the field as "tree right" use property annotation @openTag:TreeRight
     * it will use this field to store tree right value
     */
    
    /**
     * @openTag:TreeParent
     * in every tree there should be link to parent. To identify a relation
     * as parent relation to child use @Tree:Ancestor annotation on the related property
     */

    /**
     * @openTag:TreeLevel
     * level of node.
     */
}