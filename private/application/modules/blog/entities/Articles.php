<?php
namespace Application\Entities;

use Gedmo\Mapping\Annotation AS Gedmo, 
    Doctrine\ORM\Mapping AS ORM,
    Doctrine\Common\Collections\ArrayCollection, 
    Gedmo\Timestampable\Timestampable;
/**
 * Permissions
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity
 */
class Articles implements Timestampable
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
     * @gedmo:Timestampable(on="update")
     * dates which should be updated on update and insert
     * @var timestamp $action
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modified;
    
    /**
     * @gedmo:Timestampable(on="create")
     * dates which should be updated on insert only
     * @var timestamp $action
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;
    
    /**
     * @var boolean $published
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @var string $title
     *
     * @ORM\Column(type="string", length="128", nullable=true)
     */
    private $title;
    
    /**
     * @var text $content
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    
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