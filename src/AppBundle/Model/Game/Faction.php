<?php

namespace AppBundle\Model\Game;

abstract class Faction implements \JsonSerializable
{
    /** @var int **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $description;
    /** @var string **/
    protected $color;
    /** @var string **/
    protected $banner;
    
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
    
    public function setBanner(string $banner): Faction
    {
        $this->banner = $banner;
        
        return $this;
    }
    
    public function getBanner(): string
    {
        return $this->banner;
    }
    
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'banner' => $this->banner,
        ];
    }
}