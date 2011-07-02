<?php
namespace Application\Entities;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * Permissions
 *
 * @Table(name="permissions")
 * @Entity
 */
class Permissions
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean $allowed
     *
     * @Column(name="allowed", type="boolean", nullable=true)
     */
    private $allowed;

    /**
     * @var text $action
     *
     * @Column(name="action", type="text", nullable=true)
     */
    private $action;

    /**
     * @var Roles
     *
     * @ManyToMany(targetEntity="Roles", mappedBy="permission")
     */
    private $role;

    /**
     *
     * @param type $name
     * @param type $value 
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    /**
     *
     * @param type $name
     * @return type 
     */
    public function __get($name) {
        return $this->$name;
    }    
}