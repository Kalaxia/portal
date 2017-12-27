<?php

namespace AppBundle\Model\Vote;

abstract class Option
{
    /** @var integer **/
    protected $id;
    /** @var Poll **/
    protected $poll;
    /** @var string **/
    protected $value;
    
    /**
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param Poll $poll
     * @return $this
     */
    public function setPoll(Poll $poll)
    {
        $this->poll = $poll;
        
        return $this;
    }
    
    /**
     * @return Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }
    
    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}