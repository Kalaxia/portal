<?php

namespace AppBundle\Model\Project;

abstract class Feedback implements \JsonSerializable
{
    /** @var string **/
    protected $id;
    /** @var string **/
    protected $title;
    /** @var string **/
    protected $description;
    /** @var array **/
    protected $author;
    /** @var string **/
    protected $status;
    /** @var array **/
    protected $comments = [];
    /** @var DateTime **/
    protected $createdAt;
    /** @var DateTime **/
    protected $updatedAt;
    
    const TYPE_BUG = 'bug';
    const TYPE_EVOLUTION = 'evo';
    
    const STATUS_TO_SPECIFY = 'to_specify';
    const STATUS_READY = 'ready';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_TO_VALIDATE = 'to_validate';
    const STATUS_TO_DEPLOY = 'to_deploy';
    const STATUS_DONE = 'done';
    
    /**
     * @return string
     */
    abstract public function getType();
    
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param string $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param mixed $comment
     * @return $this
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_TO_SPECIFY,
            self::STATUS_READY,
            self::STATUS_IN_PROGRESS,
            self::STATUS_TO_VALIDATE,
            self::STATUS_TO_DEPLOY,
            self::STATUS_DONE
        ];
    }
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'comments' => $this->comments,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}