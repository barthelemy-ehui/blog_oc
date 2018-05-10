<?php
namespace App\Repositories;


use App\models\Comment;

class CommentRepository extends Repository implements IRepository
{
    
    public function getAll()
    {
        $sqlStmt = 'SELECT * FROM comments';
        $stmt = $this->prepare($sqlStmt);
        $stmt->execute();
        
        return $stmt->fetchAll(
          PDO::FETCH_CLASS,
          Comment::class
        );
    }
    
    public function getById($id)
    {
        $sqlStmt = 'SELECT * FROM comments WHERE id = :id';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
           ':id' => $id
        ]);
        
        return $stmt->fetchObject(Comment::class);
    }
    
    public function updateComment($data){
        $data = array_merge($data, [
           'update_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
        ]);
    
        $sqlStmt = <<<BEGIN
            UPDATE comments SET
            status = :status,
            update_at = :update_at
            WHERE id = :id
BEGIN;
        
            $stmt = $this->pdo->prepare($sqlStmt);
            $comment = $stmt->execute($data);
            
            return $comment;
    }
    
    public function insertNewComment($data) {
            
            $data = array_merge($data, [
                'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
                'status' => Comment::PENDING
            ]);
            
            $sqlStmt = <<<BEGIN
                INSERT INTO comments
                (
                  title,
                  content,
                  status,
                  email,
                  post_id,
                  create_id
                ) VALUES (
                  :title,
                  :content,
                  :status,
                  :email,
                  :post_id,
                  :create_at
                )
BEGIN;

            $stmt = $this->pdo->prepare($data);
            $stmt->execute($data);
            
            return $this->getById($this->pdo->lastInsertId());
    }
    
    public function deleteCommentById($id){
            $sqlStmt = <<<BEGIN
                DELETE FROM comments
                WHERE id = :id
BEGIN;

            $stmt = $this->pdo->prepare($sqlStmt);
            $stmt->execute([
               ':id' => $id
            ]);
    }
}