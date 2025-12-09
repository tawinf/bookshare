<?php
class Book {
    private $conn;
    private $table_name = 'books';
    

    public $id;
    public $user_id; // <-- เพิ่ม property นี้
    public $title;
    public $author;
    public $description;
    public $image; // <-- เพิ่ม property ใหม่
    public $owner_username; // <-- 1. เพิ่ม property ใหม่สำหรับเก็บชื่อเจ้าของ
    public $status;



    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all books
    public function read() {
        $query = 'SELECT id, user_id, title, author, description, image, status FROM ' . $this->table_name . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ปรับปรุงเมธอด readOne() ทั้งหมด
    public function readOne() {
        // 2. เขียน Query ใหม่โดยใช้ JOIN
        $query = "SELECT 
                    b.user_id, b.title, b.author, b.description, b.image, b.status,
                    u.username as owner_username 
                  FROM 
                    " . $this->table_name . " b
                  JOIN 
                    users u ON b.user_id = u.id
                  WHERE 
                    b.id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->user_id = $row['user_id'];
            $this->title = $row['title'];
            $this->author = $row['author'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->status = $row['status'];
            $this->owner_username = $row['owner_username']; // <-- 3. กำหนดค่าให้ property ใหม่
            
            return true;
        }
        return false;
    }


    // ปรับปรุงเมธอด create()
    public function create() {
        // เพิ่ม image เข้าไปใน query
        $query = 'INSERT INTO ' . $this->table_name . ' SET user_id=:user_id, title=:title, author=:author, description=:description, image=:image, status=:status';
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image)); // Sanitize image 
        $this->status = htmlspecialchars(strip_tags($this->status));


        $stmt->bindParam(':user_id', $this->user_id); // <-- Bind user_id
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image); // Bind image name
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }
    

    // ปรับปรุงเมธอด update()
    public function update() {
        // เพิ่ม image เข้าไปใน query
        $query = 'UPDATE ' . $this->table_name . '
                  SET title = :title, author = :author, description = :description, image = :image, status=:status
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image)); // Sanitize image name
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image); // Bind image name
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a book
    public function delete() {
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // เมธอดสำหรับอัปเดตสถานะของหนังสือโดยเฉพาะ
    public function updateStatus($status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * เมธอดใหม่สำหรับหน้า Showcase
     * ดึงข้อมูลหนังสือทั้งหมดพร้อมชื่อผู้ใช้ของเจ้าของ
     */
    public function readAllWithUsers() {
        $query = "SELECT 
                    b.id, b.title, b.author, b.image, b.status,
                    u.username 
                  FROM 
                    " . $this->table_name . " b
                  JOIN 
                    users u ON b.user_id = u.id
                  ORDER BY 
                    b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // การดึงข้อมูลเพื่อสร้าง Dashboard

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getStatusBreakdown() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table_name . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>