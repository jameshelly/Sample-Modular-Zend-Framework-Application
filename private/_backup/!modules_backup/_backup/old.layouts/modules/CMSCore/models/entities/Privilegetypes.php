<?php

/**
 * Privilegetypes
 *
 * @Table(name="privilegetypes")
 * @Entity
 */
class Privilegetypes
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
     * @Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;
    
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set privilege
     *
     * @param string $name
     */
    public function setName($privilege)
    {
        $this->name = $name;
    }

    /**
     * Get privilege
     *
     * @return string $privilege
     */
    public function getName()
    {
        return $this->name;
    }
    

    
    
}