<?php

namespace AppBundle\Model\Game;

class Server
{
    /** @var integer **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
    /** @var string **/
    protected $description;
    /** @var string **/
    protected $banner;
    /** @var string **/
    protected $signature;
    /** @var Machine **/
    protected $machine;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $startedAt;
    /** @var \DateTime **/
    protected $updatedAt;
    
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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $banner
     * @return $this
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
        
        return $this;
    }
        
    /**
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }
    
    /**
     * @param string $signature
     * @return string
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
    
    public function setMachine(Machine $machine): Server
    {
        $this->machine = $machine;
        
        return $this;
    }
    
    public function getMachine(): Machine 
    {
        return $this->machine;
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
     * @param \DateTime $startedAt
     * @return $this
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        $this->startedAt = $startedAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}