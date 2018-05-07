<?php
namespace App\Repositories;


use App\Models\Post;

class PostRepository extends Repository implements IRepository
{
    
    public function getAll()
    {
        $sqlStmt = 'SELECT * FROM posts';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute();
        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            Post::class
            );
        
    }
    
    public function getById($id)
    {
        $sqlStmt = 'SELECT * FROM posts WHERE id = :id';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
           ':id' => $id
        ]);
        
        return $stmt->fetchObject(Post::class);
    }
    
    public function updatePost($data) {
        
        $data = array_merge($data, [
            'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
        ]);
    
        $sqlStmt = <<<BEGIN
          UPDATE posts SET
          slug = :slug,
          title = :title,
          description = :description,
          content = :content,
          status = :status,
          author_id = :author_id,
          create_at = :create_at,
          publish_at = :publish_at
BEGIN;
    
        $stmt = $this->pdo->prepare($sqlStmt);
        $post = $stmt->execute($data);
        
        return $post;
    }
    
    public function insertNewPost($data) {
        
        $data = array_merge($data,[
            'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
        ]);
        
        $sqlStmt = <<<BEGIN
          INSERT INTO posts
          (
            slug,
            title,
            description,
            content,
            status,
            author_id,
            publish_at
          ) VALUES (
            :slug,
            :title,
            :description,
            :content,
            :status,
            :author_id,
            :publish_at
          )
BEGIN;
        
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute($data);
        
        return $this->getById($this->pdo->lastInsertId());
    }
    
    public function deletePostById($id) {
        $sqlStmt = <<<BEGIN
            DELETE FROM posts
            WHERE id = :id
BEGIN;
        
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
            ':id' => $id
        ]);
    }
}