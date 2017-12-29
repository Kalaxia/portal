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
    /** @var Option **/
    protected $winningOption;
    /** @var int **/
    protected $score;
    /** @var int **/
    protected $nbVotes;
    
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
    
    /**
     * @param Option $option
     * @return $this
     */
    public function setWinningOption(Option $option)
    {
        $this->winningOption = $option;
        
        return $this;
    }
    
    /**
     * @return Option
     */
    public function getWinningOption()
    {
        return $this->winningOption;
    }
    
    /**
     * @param int $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }
    
    /**
     * @param int $nbVotes
     * @return $this
     */
    public function setNbVotes($nbVotes)
    {
        $this->nbVotes = $nbVotes;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNbVotes()
    {
        return $this->nbVotes;
    }
}