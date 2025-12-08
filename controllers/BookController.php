<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Book.php';

class BookController {

    // เพิ่ม private method สำหรับตรวจสอบสิทธิ์ และเรียกใช้ในเมธอดที่ต้องการ
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    

    private function checkAdmin() {
        $this->checkAuth(); // ตรวจสอบว่า Login หรือยังก่อน
        if ($_SESSION['user_role'] !== 'admin') {
            // อาจจะส่งไปหน้า "Access Denied" หรือแค่ die()
            die('คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (สำหรับ Admin เท่านั้น)');
        }
    }

    
    // แสดงรายการหนังสือทั้งหมด
    public function index() {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $stmt = $book->read();
        // เพิ่มการดึงข้อมูลรูปภาพ
        $query = 'SELECT id, title, author, description, image, status FROM books ORDER BY created_at DESC';
        $stmt = $db->prepare($query);
        $stmt->execute();
        include_once __DIR__ . '/../views/books/index.php';
    }

    // แสดงฟอร์มสร้างหนังสือ
    public function create() {
        $this->checkAdmin();
        include_once __DIR__ . '/../views/books/create.php';
    }

    
    // ปรับปรุง store() สำหรับบันทึกข้อมูลใหม่
    public function store() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imageName = $this->handleImageUpload(); // จัดการอัปโหลดรูป

            $database = new Database();
            $db = $database->getConnection();
            $book = new Book($db);

            $book->title = $_POST['title'];
            $book->author = $_POST['author'];
            $book->description = $_POST['description'];
            $book->status = $_POST['status'];
            $book->image = $imageName; // กำหนดชื่อไฟล์รูป
            $book->user_id = $_SESSION['user_id'];

            if ($book->create()) {
                header('Location: index.php?controller=book&action=index');
            } else {
                die('ไม่สามารถสร้างข้อมูลหนังสือได้');
            }
        }
    }

    // แสดงฟอร์มแก้ไขหนังสือ
    public function edit($id) {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $book->id = $id;

        if (!$book->readOne()) {
            die('ไม่พบข้อมูลหนังสือ');
        }
        include_once __DIR__ . '/../views/books/edit.php';
    }


    // ปรับปรุง update() สำหรับอัปเดตข้อมูล
    public function update() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentImage = $_POST['current_image'];
            $imageName = $this->handleImageUpload($currentImage); // จัดการอัปโหลด (อาจมีไฟล์เก่า)

            $database = new Database();
            $db = $database->getConnection();
            $book = new Book($db);

            $book->id = $_POST['id'];
            $book->title = $_POST['title'];
            $book->author = $_POST['author'];
            $book->description = $_POST['description'];
            $book->status = $_POST['status'];
            $book->image = $imageName;

            if ($book->update()) {
                header('Location: index.php?controller=book&action=index');
            } else {
                die('ไม่สามารถอัปเดตข้อมูลหนังสือได้');
            }
        }
    }

    // ปรับปรุง destroy() สำหรับลบรูป
    public function destroy($id) {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $book->id = $id;

        // ดึงชื่อไฟล์รูปเก่ามาก่อนลบ
        if ($book->readOne()) {
            if ($book->delete()) {
                // ถ้าลบข้อมูลใน DB สำเร็จ ให้ลบไฟล์รูปด้วย
                if ($book->image && file_exists(__DIR__ . '/../public/uploads/' . $book->image)) {
                    unlink(__DIR__ . '/../public/uploads/' . $book->image);
                }
                header('Location: index.php?controller=book&action=index');
            } else {
                die('ไม่สามารถลบข้อมูลหนังสือได้');
            }
        } else {
            die('ไม่พบข้อมูลหนังสือ');
        }
    }

    // เมธอดสำหรับจัดการการอัปโหลดไฟล์โดยเฉพาะ
    private function handleImageUpload($currentImage = null) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            $fileName = basename($_FILES['image']['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileType, $allowedTypes)) {
                die('ประเภทไฟล์ไม่ได้รับอนุญาต');
            }

            // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกันเพื่อป้องกันการเขียนทับ
            $newFileName = uniqid() . '-' . $fileName;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // ถ้าอัปโหลดไฟล์ใหม่สำเร็จ และมีไฟล์เก่าอยู่ ให้ลบไฟล์เก่าทิ้ง
                if ($currentImage && file_exists($uploadDir . $currentImage)) {
                    unlink($uploadDir . $currentImage);
                }
                return $newFileName; // คืนค่าชื่อไฟล์ใหม่
            } else {
                die('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');
            }
        }
        // ถ้าไม่มีการอัปโหลดไฟล์ใหม่ ให้ใช้ชื่อไฟล์เดิม
        return $currentImage;
    }

}
?>