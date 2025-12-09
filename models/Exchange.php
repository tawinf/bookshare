<?php
class Exchange {
    private $conn;
    private $table_name = 'exchanges';

    public $id;
    public $book_id;
    public $owner_id;
    public $requester_id;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // สร้างคำขอแลกเปลี่ยนใหม่
    public function create() {
        $query = 'INSERT INTO ' . $this->table_name . ' SET book_id=:book_id, owner_id=:owner_id, requester_id=:requester_id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':owner_id', $this->owner_id);
        $stmt->bindParam(':requester_id', $this->requester_id);

        return $stmt->execute();
    }
    
    // (สามารถเพิ่มเมธอดสำหรับดึงข้อมูลคำขอ หรืออัปเดตสถานะได้ที่นี่)
    // อ่านข้อมูลคำขอเดียว
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->book_id = $row['book_id'];
            $this->owner_id = $row['owner_id'];
            $this->requester_id = $row['requester_id'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // อัปเดตสถานะของคำขอ (เช่น 'accepted', 'rejected')
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // ตรวจสอบว่าเคยส่งคำขอสำหรับหนังสือเล่มนี้ไปแล้วหรือยัง
    public function hasPendingRequest($book_id, $requester_id) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE book_id = ? AND requester_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $book_id);
        $stmt->bindParam(2, $requester_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // การดึงข้อมูลเพื่อสร้าง Dashboard

    public function countByStatus($status = 'pending') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // ดึงสถิติคำขอใหม่ย้อนหลัง 7 วัน
    public function getNewRequestsLast7Days() {
        $query = "SELECT DATE(created_at) as request_date, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE created_at >= CURDATE() - INTERVAL 7 DAY
                  GROUP BY DATE(created_at)
                  ORDER BY DATE(created_at) ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ดึงข้อมูลคำขอแลกเปลี่ยนทั้งหมดในระบบสำหรับ Admin
     */
    public function readAll() {
        $query = "SELECT 
                    e.id, 
                    e.status, 
                    e.created_at,
                    b.title as book_title,
                    owner.username as owner_name,
                    requester.username as requester_name
                  FROM 
                    " . $this->table_name . " e
                  JOIN 
                    books b ON e.book_id = b.id
                  JOIN 
                    users owner ON e.owner_id = owner.id
                  JOIN 
                    users requester ON e.requester_id = requester.id
                  ORDER BY 
                    e.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * เมธอดใหม่สำหรับ Dashboard
     * ดึงข้อมูลสรุปจำนวนคำขอในแต่ละสถานะ
     */
    public function getStatusBreakdown() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table_name . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>