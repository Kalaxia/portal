<?php

namespace App\Model\Project;

class Feedback implements \JsonSerializable
{
    /** @var string **/
    protected $id;
    /** @var string **/
    protected $title;
    /** @var string **/
    protected $type;
    /** @var string **/
    protected $slug;
    /** @var string **/
    protected $description;
    /** @var array **/
    protected $author;
    /** @var string **/
    protected $status;
    /** @var array **/
    protected $comments = [];
    /** @var array **/
    protected $labels = [];
    /** @var DateTime **/
    protected $createdAt;
    /** @var DateTime **/
    protected $updatedAt;
    
    const TYPE_BUG = 'bug';
    const TYPE_EVOLUTION = 'evolution';
    
    const STATUS_TO_SPECIFY = 'to_specify';
    const STATUS_READY = 'ready';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_TO_VALIDATE = 'to_validate';
    const STATUS_TO_DEPLOY = 'to_deploy';
    const STATUS_DONE = 'done';
    
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
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type=  $type;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
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
     * @param Label $label
     * @return $this
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;
        
        return $this;
    }
    
    /**
     * @param Label $label
     * @return boolean
     */
    public function hasLabel(Label $label)
    {
        return in_array($label, $this->labels);
    }
    
    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
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
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'author' => $this->author,
            'labels' => $this->labels,
            'comments' => $this->comments,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}