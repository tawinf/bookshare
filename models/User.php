<?php
class User {
    private $conn;
    private $table_name = 'users';

    public $id;
    public $username;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // In class User { ... }

    public function isUsernameExists() {
        $query = 'SELECT id FROM ' . $this->table_name . ' WHERE username = :username LIMIT 1';
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(':username', $this->username);
    
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; // Username found
        }
        return false; // Username not found
    }

    // ลงทะเบียนผู้ใช้ใหม่
    public function register() {
        $query = 'INSERT INTO ' . $this->table_name . ' SET username = :username, password = :password, role = :role';
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->role = htmlspecialchars(strip_tags($this->role));
        
        // Hash the password before saving
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind data
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ตรวจสอบการล็อกอิน
    public function login() {
        $query = 'SELECT id, username, password, role FROM ' . $this->table_name . ' WHERE username = :username LIMIT 0,1';
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    /**
     * ส่วนที่เพิ่มเข้ามาสำหรับการจัดการ Users (CRUD)
     */

    // อ่านข้อมูลผู้ใช้ทั้งหมด
    public function read() {
        $query = 'SELECT id, username, role, created_at FROM ' . $this->table_name . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // อ่านข้อมูลผู้ใช้คนเดียว
    public function readOne() {
        $query = 'SELECT username, role FROM ' . $this->table_name . ' WHERE id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->username = $row['username'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    // อัปเดตข้อมูลผู้ใช้ (Admin สามารถเปลี่ยน username และ role)
    public function update() {
        // ถ้ามีการส่งรหัสผ่านใหม่มาด้วย ให้ hash ใหม่
        if (!empty($this->password)) {
             $query = 'UPDATE ' . $this->table_name . ' SET username = :username, password = :password, role = :role WHERE id = :id';
             $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        } else {
             $query = 'UPDATE ' . $this->table_name . ' SET username = :username, role = :role WHERE id = :id';
        }
       
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);

        if (!empty($this->password)) {
            $stmt->bindParam(':password', $this->password);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ลบผู้ใช้
    public function delete() {
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // การดึงข้อมูลเพื่อสร้าง Dashboard

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    
}
?>