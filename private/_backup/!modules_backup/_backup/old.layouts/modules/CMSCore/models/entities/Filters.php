<?php



/**
 * Filters
 *
 * @Table(name="filters")
 * @Entity
 */
class Filters
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
     * @var integer $variableId
     *
     * @Column(name="variable_id", type="integer", nullable=false)
     */
    private $variableId;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string $filterActions
     *
     * @Column(name="filter_actions", type="string", length=45, nullable=true)
     */
    private $filterActions;

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
     * Set variableId
     *
     * @param integer $variableId
     */
    public function setVariableId($variableId)
    {
        $this->variableId = $variableId;
    }

    /**
     * Get variableId
     *
     * @return integer $variableId
     */
    public function getVariableId()
    {
        return $this->variableId;
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
     * Set filterActions
     *
     * @param string $filterActions
     */
    public function setFilterActions($filterActions)
    {
        $this->filterActions = $filterActions;
    }

    /**
     * Get filterActions
     *
     * @return string $filterActions
     */
    public function getFilterActions()
    {
        return $this->filterActions;
    }
}