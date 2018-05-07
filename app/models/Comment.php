<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 02/05/2018
 * Time: 15:21
 */

namespace App\models;


class Comment extends Model
{
    protected $title;
    protected $content;
    protected $status;
    protected $post_id;
    
    const PENDING = 'pending';
    const PUBLISHED = 'published';
    
    const TITLE = 'title';
    const CONTENT = 'content';
    
    const STATUS = [
        self::PENDING,
        self::PUBLISHED
    ];
    
    const POST_ID = 'post_id';
    
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
}