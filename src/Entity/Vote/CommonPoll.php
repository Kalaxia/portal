<?php

namespace App\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vote__common_polls")
 */
class CommonPoll extends Poll
{
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return Poll::TYPE_COMMON;
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}