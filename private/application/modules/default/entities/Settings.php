<?php
namespace Application\Entities;

use Doctrine\Common\Collections\ArrayCollection, Gedmo\Timestampable\Timestampable;
/**
 * Permissions
 *
 * @Table(name="settings")
 * @Entity
 */
class Settings implements Timestampable
{
    /**
     * @var integer $id
     *
     * @Column(type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean $allowed
     *
     * @Column(type="boolean", nullable=true)
     */
    private $allowed;

    /**
     * @var string $action
     *
     * @Column(type="string", length="128", nullable=true)
     */
    private $type;
    
    /**
     * @var text $action
     *
     * @Column(type="text", nullable=true)
     */
    private $parameters;
    
    /**
     * @gedmo:Timestampable(on="update")
     * dates which should be updated on update and insert
     * @var timestamp $action
     *
     * @Column(type="datetime", nullable=false)
     */
    private $modified;
    
    /**
     * @gedmo:Timestampable(on="create")
     * dates which should be updated on insert only
     * @var timestamp $action
     *
     * @Column(type="datetime", nullable=false)
     */
    private $created;

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