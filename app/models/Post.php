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
    protected $published_at;
    
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
    public function getPublishedAt()
    {
        return $this->published_at;
    }
    
    /**
     * @param mixed $published_at
     */
    public function setPublishedAt($published_at): void
    {
        $this->published_at = $published_at;
    }
}