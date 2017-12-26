<?php

namespace AppBundle\Model\Vote;

abstract class Poll
{
    /** @var int **/
    protected $id;
    /** @var string **/
    protected $createdAt;
    /** @var string **/
    protected $endedAt;
    /** @var boolean **/
    protected $isOver;
    
    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $endedAt
     * @return $this
     */
    public function setEndedAt(\DateTime $endedAt)
    {
        $this->endedAt = $endedAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }
    
    /**
     * @param boolean $isOver
     * @return $this
     */
    public function setIsOver($isOver)
    {
        $this->isOver = $isOver;
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsOver()
    {
        return $this->isOver;
    }
}