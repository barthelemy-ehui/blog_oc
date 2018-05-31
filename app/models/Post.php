<?php
namespace App\Models;

class Post extends Model
{
    protected $slug;
    protected $title;
    protected $description;
    protected $content;
    protected $status;
    protected $author_id;
    protected $publish_at;
    
    const PENDING = 'pending';
    const PUBLISHED = 'published';
    
    const SLUG = 'slug';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const CONTENT = 'content';
    
    const STATUS = 'status';
    
    const AUTHOR_ID = 'author_id';
    const PUBLISH_AT = 'publish_at';
    
    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
    
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
    
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }
    
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
    
    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }
    
    /**
     * @param mixed $author_id
     */
    public function setAuthorId($author_id): void
    {
        $this->author_id = $author_id;
    }
    
    /**
     * @return mixed
     */
    public function getPublishAt()
    {
        return $this->publish_at;
    }
    
    /**
     * @param mixed $publish_at
     */
    public function setPublishAt($publish_at): void
    {
        $this->publish_at = $publish_at;
    }
    
}