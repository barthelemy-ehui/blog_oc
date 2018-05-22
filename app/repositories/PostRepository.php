<?php
namespace App\Repositories;

use App\Models\Post;

class PostRepository extends Repository implements IRepository
{
    
    public function getAll()
    {
        $sqlStmt = 'SELECT * FROM posts';
        $stmt = $this->pdo->query($sqlStmt);
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Post::class
        );
        
    }
    
    public function getAllByPagination($offset, $limit)
    {
        
        $sqlStmt = 'SELECT * FROM posts WHERE status = :status AND publish_at<=NOW() ORDER BY update_at DESC LIMIT :offset, :limit';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->bindValue(':offset', $offset*$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':status', POST::PUBLISHED, \PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Post::class
        );
    }
    
    public function countAll()
    {
        
        $sqlStmt = 'SELECT count(*) FROM posts WHERE status = :status AND publish_at<=NOW()';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->bindValue(':status', POST::PUBLISHED, \PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_NUM)[0];
    }
    
    public function getById($id)
    {
        $sqlStmt = 'SELECT * FROM posts WHERE id = :id';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute(
            [
            'id' => $id
            ]
        );
        
        return $stmt->fetchObject(Post::class);
    }
    
    
    public function getBySlug($slug)
    {
        $sqlStmt = 'SELECT * FROM posts WHERE slug = :slug';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute(
            [
            ':slug' => $slug
            ]
        );
        
        return $stmt->fetchObject(Post::class);
    }
    
    public function updatePost($data) 
    {
        
        $data = array_merge(
            $data, [
            'update_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
            ]
        );
    
        $sqlStmt = <<<BEGIN
          UPDATE posts SET
          slug = :slug,
          title = :title,
          description = :description,
          content = :content,
          status = :status,
          update_at = :update_at,
          publish_at = :publish_at
          WHERE id = :id
BEGIN;
    
        $stmt = $this->pdo->prepare($sqlStmt);
        return $stmt->execute($data);
    }
    
    public function insertNewPost($data) 
    {
        
        $data = array_merge(
            $data, [
            'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
            'update_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
            ]
        );
        
        $sqlStmt = <<<BEGIN
          INSERT INTO posts (slug,title,description,content,status,author_id,publish_at,create_at,update_at) VALUES (:slug,:title,:description,:content,:status,:author_id,:publish_at,:create_at,:update_at);
BEGIN;
        
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute($data);
        
        return $this->getById($this->pdo->lastInsertId());
    }
    
    public function deletePostById($id) 
    {
        $sqlStmt = <<<BEGIN
            DELETE FROM posts
            WHERE id = :id
BEGIN;
        
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
    }
    
    public function getCount()
    {
        $sqlStmt = 'SELECT count(*) FROM posts';
        $stmt = $this->pdo->query($sqlStmt);
    
        return $stmt->fetch(\PDO::FETCH_NUM)[0];
    }
    
    public function getTheTreeLatestPosts()
    {
        
        $sqlStmt = 'SELECT * FROM posts WHERE status = :status AND publish_at<=NOW() ORDER BY update_at DESC LIMIT :limit';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->bindValue(':limit', 3, \PDO::PARAM_INT);
        $stmt->bindValue(':status', POST::PUBLISHED, \PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Post::class
        );
    }
}
