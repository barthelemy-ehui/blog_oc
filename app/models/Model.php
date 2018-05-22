<?php
namespace App\models;

class Model
{
    protected $id;
    protected $create_at;
    protected $update_at;
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }
    
    /**
     * @param mixed $create_at
     */
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }
    
    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }
    
    /**
     * @param mixed $update_at
     */
    public function setUpdateAt($update_at): void
    {
        $this->update_at = $update_at;
    }
    
}