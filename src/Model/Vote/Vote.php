<?php

namespace App\Model\Vote;

use App\Entity\User;

abstract class Vote
{
    /** @var User **/
    protected $user;
    /** @var Poll **/
    protected $poll;
    /** @var Option **/
    protected $option;
    
    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
     * @param Option $option
     * @return $this
     */
    public function setOption(Option $option)
    {
        $this->option = $option;
        
        return $this;
    }
    
    /**
     * @return Option
     */
    public function getOption()
    {
        return $this->option;
    }
}