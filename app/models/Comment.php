<?php
namespace App\models;

class Comment extends Model
{
    protected $title;
    protected $content;
    protected $status;
    protected $email;
    protected $post_id;
    
    public const PENDING = 'pending';
    public const PUBLISHED = 'published';
    public const REFUSED = 'refused';
    
    public const TITLE = 'title';
    public const CONTENT = 'content';
    
    public const STATUS = 'status';
    
    public const POST_ID = 'post_id';
    
    public const IS_SENT = 'isSent';
    
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
    public function getPostId()
    {
        return $this->post_id;
    }
    
    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id): void
    {
        $this->post_id = $post_id;
    }
    
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}
