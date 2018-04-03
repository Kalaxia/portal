<?php

namespace AppBundle\Model\Project;

class Label implements \JsonSerializable
{
    /** @var string **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $color;
    
    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return string
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
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
        ];
    }
}