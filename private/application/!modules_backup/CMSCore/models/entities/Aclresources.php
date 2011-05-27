<?php



/**
 * aclresources
 *
 * @Table(name="aclresources")
 * @Entity
 */
class Aclresources
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
     * @var integer $buildorder
     *
     * @Column(name="buildorder", type="integer" )
     */
    private $buildorder;
    
    
    /**
     * @var string $type
     *
     * @Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;
    
    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Aclresources
     *
     * @ManyToOne(targetEntity="Aclresources")
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
    	return $this->type;
    }
    
	/**
     * Set buildorder
     *
     * @param integer $buildorder
     */
    public function setBuildOrder($buildorder)
    {
        $this->buildorder = $buildorder;
    }
     

    /**
     * Get order
     *
     * @return integer $buildorder
     */
    public function getBuildOrder()
    {
    	return $this->buildorder;
    }
    
    
    
    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    

    /**
     * Set parent
     *
     * @param Resources $parent
     */
    public function setParent(\Aclresources $parent)
    {
        $this->parent = $parent;
    }
    
   

    /**
     * Get parent
     *
     * @return Resources $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
    
   
}