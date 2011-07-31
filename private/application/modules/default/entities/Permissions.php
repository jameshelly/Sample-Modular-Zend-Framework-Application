<?php
namespace Application\Entities;

use Gedmo\Mapping\Annotation AS Gedmo, 
    Doctrine\ORM\Mapping AS ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * Permissions
 *
 * @ORM\Table(name="permissions")
 * @ORM\Entity
 */
class Permissions
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean $allowed
     *
     * @ORM\Column(name="allowed", type="boolean", nullable=true)
     */
    private $allowed;

    /**
     * @var text $action
     *
     * @ORM\Column(name="action", type="text", nullable=true)
     */
    private $action;

    /**
     * @var Roles
     *
     * @ORM\ManyToMany(targetEntity="Roles", mappedBy="permission")
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