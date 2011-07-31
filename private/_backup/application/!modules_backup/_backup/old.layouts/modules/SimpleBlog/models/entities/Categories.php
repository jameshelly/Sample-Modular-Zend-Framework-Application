<?php

/**
 * Categories
 *
 * @Table(name="categories")
 * @Entity
 */
class Categories
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
     * @Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var text $param
     *
     * @Column(name="param", type="text", nullable=true)
     */
    private $param;

    /**
     * @var Categories
     *
     * @ManyToOne(targetEntity="Categories")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set param
     *
     * @param text $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * Get param
     *
     * @return text $param
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set parent
     *
     * @param Categories $parent
     */
    public function setParent(\Categories $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Categories $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

}