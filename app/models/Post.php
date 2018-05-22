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
    
    public const PENDING = 'pending';
    public const PUBLISHED = 'published';
    
    public const SLUG = 'slug';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const CONTENT = 'content';
    
    public const STATUS = 'status';
    
    public const AUTHOR_ID = 'author_id';
    public const PUBLISH_AT = 'publish_at';
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title): void
    {
        $this->title = $title;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content): void
    {
        $this->content = $content;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status): void
    {
        $this->status = $status;
    }
    
    public function getAuthorId()
    {
        return $this->author_id;
    }
    
    public function setAuthorId($author_id): void
    {
        $this->author_id = $author_id;
    }
    
    public function getPublishAt()
    {
        return $this->publish_at;
    }
    
    public function setPublishAt($publish_at): void
    {
        $this->publish_at = $publish_at;
    }
    
}