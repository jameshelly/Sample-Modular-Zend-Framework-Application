<?php
namespace Application\Entities;

use Gedmo\Mapping\Annotation AS Gedmo, 
    Doctrine\ORM\Mapping AS ORM,
    Doctrine\Common\Collections\ArrayCollection;
/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Roles
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
     /**
     * @var Roles
     *
     * @ORM\ManyToOne(targetEntity="Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;
    
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