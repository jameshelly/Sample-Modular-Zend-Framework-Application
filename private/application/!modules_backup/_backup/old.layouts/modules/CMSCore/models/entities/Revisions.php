<?php

/**
 * Revisions
 *
 * @Table(name="revisions")
 * @Entity
 */
class Revisions
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
     * @var integer $revision
     *
     * @Column(name="revision", type="integer", nullable=false)
     */
    private $revision;

    /**
     * @var text $message
     *
     * @Column(name="message", type="text", nullable=true)
     */
    private $message;

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
     * Set revision
     *
     * @param integer $revision
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    }

    /**
     * Get revision
     *
     * @return integer $revision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set message
     *
     * @param text $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return text $message
     */
    public function getMessage()
    {
        return $this->message;
    }
}