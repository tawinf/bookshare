<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/User.php';

class UserController {

    // ตรวจสอบว่าเป็น Admin หรือไม่, ถ้าไม่ใช่ให้เด้งออก
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die('Access Denied');
        }
    }

    // แสดงรายชื่อผู้ใช้ทั้งหมด
    public function index() {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        $stmt = $user->read();
        include_once __DIR__ . '/../views/users/index.php';
    }

    // แสดงฟอร์มสร้างผู้ใช้
    public function create() {
        $this->checkAdmin();
        include_once __DIR__ . '/../views/users/create.php';
    }

    // จัดการการบันทึกผู้ใช้ใหม่
    public function store() {
        
        $this->checkAdmin();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            $user->username = $_POST['username'];
            // --- Validation ---
            if (empty($_POST['username'])) {
                $errors[] = 'กรุณากรอก Username';
            } elseif ($user->isUsernameExists()) { // <-- USE THE NEW CHECK HERE
                $errors[] = 'ชื่อผู้ใช้นี้มีคนใช้แล้ว กรุณาเลือกชื่ออื่น';
            } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $_POST['username'])) { // <-- ADDED: ตรวจสอบรูปแบบ Username
                $errors[] = 'Username ต้องเป็นตัวอักษรภาษาอังกฤษ, ตัวเลข หรือ _ และยาว 4-20 ตัวอักษรเท่านั้น';
            }

            if (empty($_POST['password'])) {
                $errors[] = 'กรุณากรอก Password';
            } elseif (strlen($_POST['password']) < 8) {
                $errors[] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร'; // <-- ADDED: ตรวจสอบความยาวรหัสผ่าน
            }


            if (empty($errors)) {
                
                $user->password = $_POST['password'];
                $user->role = $_POST['role'];

                if ($user->register()) { // ใช้เมธอด register ที่มีอยู่แล้ว
                    header('Location: index.php?controller=user&action=index&status=created');
                    exit();
                } else {
                    $errors[] = 'ไม่สามารถสร้างผู้ใช้ได้ (อาจมี Username ซ้ำ)';
                }
            }
        }
        // ถ้ามี error หรือยังไม่ได้ submit ให้กลับไปหน้า create
        include_once __DIR__ . '/../views/users/create.php';
    }

    // แสดงฟอร์มแก้ไขข้อมูลผู้ใช้
    public function edit($id) {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        $user->id = $id;

        if (!$user->readOne()) {
            die('ไม่พบผู้ใช้ที่ต้องการแก้ไข');
        }
        include_once __DIR__ . '/../views/users/edit.php';
    }

    // จัดการการอัปเดตข้อมูลผู้ใช้
    public function update() {
        $this->checkAdmin();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            $user->id = $_POST['id'];
            $user->role = $_POST['role'];
            $user->username = $_POST['username']; 

            
            // อัปเดตรหัสผ่านถ้ามีการกรอกใหม่เท่านั้น
            if (!empty($_POST['password'])) {
                $user->password = $_POST['password'];    
            }

            if ($user->update()) {
                header('Location: index.php?controller=user&action=index&status=updated');
                exit();
            } else {
                die('ไม่สามารถอัปเดตข้อมูลผู้ใช้ได้');
            }

        }
        
    }
    
    // จัดการการลบผู้ใช้
    public function destroy($id) {
        $this->checkAdmin();
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        $user->id = $id;

        if ($user->delete()) {
            header('Location: index.php?controller=user&action=index&status=deleted');
            exit();
        } else {
            die('ไม่สามารถลบผู้ใช้ได้');
        }
    }
}
?>