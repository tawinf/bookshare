<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Book.php';

class HomeController {

    // เมธอดนี้จะทำงานเมื่อเปิดหน้าแรก
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);

        // เรียกใช้เมธอดใหม่ที่จะดึงหนังสือทั้งหมดพร้อมชื่อเจ้าของ
        $stmt = $book->readAllWithUsers();

        // ส่งข้อมูลไปแสดงผลที่ View ใหม่
        include_once __DIR__ . '/../views/home/index.php';
    }
}
?>