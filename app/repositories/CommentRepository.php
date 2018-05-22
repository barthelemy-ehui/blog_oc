<?php
namespace App\Repositories;

use App\models\Comment;
use App\Models\Post;

class CommentRepository extends Repository implements IRepository
{
    
    public function getAll()
    {
        $sqlStmt = <<<BEGIN
        SELECT
          c.id,
          c.title,
          c.content,
          c.status,
          c.email,
          c.post_id,
          c.create_at,
          c.update_at,
          p.slug
          FROM comments as c
          JOIN
          posts as p
          ON c.post_id = p.id
          ORDER BY c.create_at
          DESC
BEGIN;
        
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute();
        
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Comment::class
        );
    }
    
    public function getById($id)
    {
        $sqlStmt = 'SELECT * FROM comments WHERE id = :id';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        
        return $stmt->fetchObject(Comment::class);
    }
    
    public function getCommentsByPost($postId)
    {
        $sqlStmt = 'SELECT * FROM comments WHERE post_id = :post_id AND status = :status';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute(
            [
            ':post_id' => $postId,
            ':status' => Comment::PUBLISHED
            ]
        );
        
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Post::class
        );
    }
    
    public function updateComment($data)
    {
        $data = array_merge(
            $data, [
            'update_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
            ]
        );
    
        $sqlStmt = <<<BEGIN
            UPDATE comments SET
            status = :status,
            update_at = :update_at
            WHERE id = :id
BEGIN;
        
            $stmt = $this->pdo->prepare($sqlStmt);
        return $stmt->execute($data);
    }
    
    public function insertNewComment($data) 
    {
            
            $data = array_merge(
                $data, [
                'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
                'status' => Comment::PENDING
                ]
            );
            
            $sqlStmt = <<<BEGIN
                INSERT INTO comments
                (
                  title,
                  content,
                  status,
                  email,
                  post_id,
                  create_at
                ) VALUES (
                  :title,
                  :content,
                  :status,
                  :email,
                  :post_id,
                  :create_at
                )
BEGIN;


            $stmt = $this->pdo->prepare($sqlStmt);
            $stmt->execute($data);
            
            return $this->getById($this->pdo->lastInsertId());
    }
    
    public function deleteCommentById($id)
    {
            $sqlStmt = <<<BEGIN
                DELETE FROM comments
                WHERE id = :id
BEGIN;

            $stmt = $this->pdo->prepare($sqlStmt);
            $stmt->execute(
                [
                ':id' => $id
                ]
            );
    }
    
    function getCount()
    {
        $sqlStmt = 'SELECT count(*) FROM comments';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_NUM)[0];
    }
}