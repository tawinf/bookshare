<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/User.php';

class AuthController {

    // แสดงหน้าฟอร์ม Login
    public function login() {
        include_once __DIR__ . '/../views/auth/login.php';
    }

    // ประมวลผลการ Login
    public function doLogin() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            $user->username = $_POST['username'];
            $user->password = $_POST['password'];

            if ($user->login()) {
                // Login สำเร็จ, เก็บข้อมูลลง Session
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['user_role'] = $user->role;
                header('Location: index.php'); // กลับไปหน้าแรก
                exit();
            } else {
                // Login ไม่สำเร็จ
                $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
                include_once __DIR__ . '/../views/auth/login.php';
            }
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
    
    /**
     * เพิ่มเมธอดสำหรับสมัครสมาชิก
     */

    // แสดงหน้าฟอร์มสมัครสมาชิก
    public function register() {
        include_once __DIR__ . '/../views/auth/register.php';
    }

    public function doRegister() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->username = $_POST['username']; // Set username for checking
        
        // --- Updated Validation ---
        if (empty($_POST['username'])) {
            $errors[] = 'กรุณากรอกชื่อผู้ใช้';
        } elseif ($user->isUsernameExists()) { // <-- USE THE NEW CHECK HERE
            $errors[] = 'ชื่อผู้ใช้นี้มีคนใช้แล้ว กรุณาเลือกชื่ออื่น';
        } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $_POST['username'])) { // <-- ADDED: ตรวจสอบรูปแบบ Username
            $errors[] = 'Username ต้องเป็นตัวอักษรภาษาอังกฤษ, ตัวเลข หรือ _ และยาว 4-20 ตัวอักษรเท่านั้น';
        }
        
        if (empty($_POST['password'])) {
            $errors[] = 'กรุณากรอกรหัสผ่าน';
        } elseif (strlen($_POST['password']) < 8) {
            $errors[] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร'; // <-- ADDED: ตรวจสอบความยาวรหัสผ่าน
        }

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $errors[] = 'รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน';
        }

        if (empty($errors)) {
            $user->password = $_POST['password'];
            $user->role = $_POST['role'];

            if ($user->register()) {
                header('Location: index.php?controller=auth&action=login&status=registered');
                exit();
            } else {
                // This else is now a fallback, the primary check is above
                $errors[] = 'เกิดข้อผิดพลาดในการสมัครสมาชิก';
            }
        }
        }
        // Show form with errors
        include_once __DIR__ . '/../views/auth/register.php';
    }

    
}
?>