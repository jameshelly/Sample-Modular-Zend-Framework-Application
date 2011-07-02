<?php
namespace Application\Entities;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * Roles
 *
 * @Table(name="roles")
 * @Entity
 */
class Roles
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;
    
     /**
     * @var Roles
     *
     * @ManyToOne(targetEntity="Roles")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;
    
     /**
     * @var integer $buildorder
     *
     * @Column(name="buildorder", type="integer" )
     */
    private $buildorder;
    
    /**
     * @var Permissions
     *
     * @ManyToMany(targetEntity="Permissions", inversedBy="role")
     * @JoinTable(name="roles_permissions",
     *   joinColumns={
     *     @JoinColumn(name="role_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="permission_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permission;
    
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