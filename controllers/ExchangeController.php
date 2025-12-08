<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Book.php';
include_once __DIR__ . '/../models/Exchange.php';

class ExchangeController {

    // ตรวจสอบว่า Login หรือยัง
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    // หน้า Marketplace แสดงหนังสือของคนอื่นที่พร้อมแลกเปลี่ยน
    public function index() {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();

        // ดึงข้อมูลหนังสือทั้งหมดที่มีสถานะ 'available' และไม่ใช่ของตัวเอง
        $query = "SELECT books.id, books.title, books.author, books.image, books.status, users.username 
                  FROM books 
                  JOIN users ON books.user_id = users.id
                  WHERE books.status = 'available' AND books.user_id != :current_user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':current_user_id', $_SESSION['user_id']);
        $stmt->execute();
        
        include_once __DIR__ . '/../views/exchange/index.php';
    }

    // หน้ารายละเอียดหนังสือ
    public function show($id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $book->id = $id;
        $book->readOne(); // ควรปรับปรุง readOne ให้ดึงข้อมูลเจ้าของมาด้วย

        include_once __DIR__ . '/../views/exchange/show.php';
    }
    
    // (สามารถเพิ่มเมธอด myBooks, create, store สำหรับให้สมาชิกจัดการหนังสือของตัวเองได้ที่นี่)
    /**
     * ======================================================
     * ส่วนที่เพิ่มเข้ามาสำหรับ "หนังสือของฉัน" (My Books)
     * ======================================================
     */

    // หน้าแสดงหนังสือของสมาชิกที่ Login อยู่
    public function myBooks() {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        
        // ดึงข้อมูลเฉพาะหนังสือที่เป็นของ user คนนี้
        $query = "SELECT id, title, author, status, image FROM books WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        
        include_once __DIR__ . '/../views/exchange/my_books.php';
    }

    // แสดงฟอร์มสำหรับเพิ่มหนังสือใหม่
    public function create() {
        $this->checkAuth();
        include_once __DIR__ . '/../views/exchange/create.php';
    }

    // จัดการการบันทึกหนังสือใหม่
    public function store() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imageName = $this->handleImageUpload(); // ใช้เมธอดจัดการรูปภาพ

            $database = new Database();
            $db = $database->getConnection();
            $book = new Book($db);

            // **CRITICAL**: กำหนดเจ้าของหนังสือคือ user ที่ login อยู่
            $book->user_id = $_SESSION['user_id'];
            $book->title = $_POST['title'];
            $book->author = $_POST['author'];
            $book->description = $_POST['description'];
            $book->status = $_POST['status'];
            $book->image = $imageName;

            if ($book->create()) {
                header('Location: index.php?controller=exchange&action=myBooks&status=created');
            } else {
                die('ไม่สามารถเพิ่มหนังสือได้');
            }
        }
    }

    // แสดงฟอร์มแก้ไขหนังสือ
    public function edit($id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $book->id = $id;

        if ($book->readOne()) {
            // **CRITICAL SECURITY CHECK**: ตรวจสอบว่าเป็นเจ้าของหนังสือจริงหรือไม่
            if ($book->user_id != $_SESSION['user_id']) {
                die('Access Denied. You are not the owner of this book.');
            }
            include_once __DIR__ . '/../views/exchange/edit.php';
        } else {
            die('ไม่พบหนังสือที่ต้องการแก้ไข');
        }
    }

    // จัดการการอัปเดตข้อมูลหนังสือ
    public function update() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $book = new Book($db);
            $book->id = $_POST['id'];

            // ดึงข้อมูลเก่าเพื่อตรวจสอบความเป็นเจ้าของ
            if ($book->readOne()) {
                 // **CRITICAL SECURITY CHECK**:
                if ($book->user_id != $_SESSION['user_id']) {
                    die('Access Denied.');
                }

                $imageName = $this->handleImageUpload($_POST['current_image']);

                // กำหนดค่าใหม่
                $book->title = $_POST['title'];
                $book->author = $_POST['author'];
                $book->description = $_POST['description'];
                $book->status = $_POST['status'];
                $book->image = $imageName;

                if ($book->update()) {
                    header('Location: index.php?controller=exchange&action=myBooks&status=updated');
                } else {
                    die('ไม่สามารถอัปเดตหนังสือได้');
                }
            }
        }
    }

    // จัดการการลบหนังสือ
    public function destroy($id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        $book = new Book($db);
        $book->id = $id;

        if ($book->readOne()) {
            // **CRITICAL SECURITY CHECK**:
            if ($book->user_id != $_SESSION['user_id']) {
                die('Access Denied.');
            }

            if ($book->delete()) {
                // ถ้าลบข้อมูลสำเร็จ ให้ลบไฟล์รูปด้วย
                if ($book->image && file_exists(__DIR__ . '/../public/uploads/' . $book->image)) {
                    unlink(__DIR__ . '/../public/uploads/' . $book->image);
                }
                header('Location: index.php?controller=exchange&action=myBooks&status=deleted');
            } else {
                die('ไม่สามารถลบหนังสือได้');
            }
        }
    }

    // เมธอดสำหรับจัดการอัปโหลดรูป (เหมือนกับใน BookController)
    private function handleImageUpload($currentImage = null) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            $fileName = basename($_FILES['image']['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) die('ประเภทไฟล์ไม่ได้รับอนุญาต');
            
            $newFileName = uniqid() . '-' . $fileName;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                if ($currentImage && file_exists($uploadDir . $currentImage)) {
                    unlink($uploadDir . $currentImage);
                }
                return $newFileName;
            }
        }
        return $currentImage;
    }

    /**
     * ======================================================
     * ส่วนที่เพิ่มเข้ามาสำหรับ "ระบบจัดการคำขอแลกเปลี่ยน"
     * ======================================================
     */

    // สร้างคำขอแลกเปลี่ยน
    public function requestExchange($book_id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        
        $book = new Book($db);
        $book->id = $book_id;

        if ($book->readOne()) {
            // ป้องกันการขอแลกหนังสือของตัวเอง
            if ($book->user_id == $_SESSION['user_id']) {
                die("คุณไม่สามารถขอแลกเปลี่ยนหนังสือของตัวเองได้");
            }

            $exchange = new Exchange($db);
            // ป้องกันการส่งคำขอซ้ำ
            if ($exchange->hasPendingRequest($book_id, $_SESSION['user_id'])) {
                 header('Location: index.php?controller=exchange&action=show&id=' . $book_id . '&status=already_requested');
                 exit();
            }

            $exchange->book_id = $book_id;
            $exchange->owner_id = $book->user_id;
            $exchange->requester_id = $_SESSION['user_id'];
            
            if ($exchange->create()) {
                header('Location: index.php?controller=exchange&action=show&id=' . $book_id . '&status=requested');
            } else {
                die("เกิดข้อผิดพลาดในการส่งคำขอ");
            }
        } else {
            die("ไม่พบหนังสือ");
        }
    }

    // หน้าจัดการคำขอ (ทั้งที่ส่งไปและที่ได้รับ)
    public function manageRequests() {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        $current_user_id = $_SESSION['user_id'];

        // 1. ดึงคำขอที่คนอื่นส่งมาหาเรา (Incoming)
        $incomingQuery = "SELECT e.id, e.status, b.title, u.username as requester_name
                          FROM exchanges e
                          JOIN books b ON e.book_id = b.id
                          JOIN users u ON e.requester_id = u.id
                          WHERE e.owner_id = :user_id";
        $incomingStmt = $db->prepare($incomingQuery);
        $incomingStmt->bindParam(':user_id', $current_user_id);
        $incomingStmt->execute();

        // 2. ดึงคำขอที่เราส่งไปหาคนอื่น (Outgoing)
        $outgoingQuery = "SELECT e.id, e.status, b.title, u.username as owner_name
                          FROM exchanges e
                          JOIN books b ON e.book_id = b.id
                          JOIN users u ON e.owner_id = u.id
                          WHERE e.requester_id = :user_id";
        $outgoingStmt = $db->prepare($outgoingQuery);
        $outgoingStmt->bindParam(':user_id', $current_user_id);
        $outgoingStmt->execute();

        include_once __DIR__ . '/../views/exchange/manage_requests.php';
    }
    
    // ยอมรับคำขอ
    public function acceptRequest($request_id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();
        
        $exchange = new Exchange($db);
        $exchange->id = $request_id;

        if ($exchange->readOne()) {
            // Security Check: ตรวจสอบว่าเป็นเจ้าของคำขอจริง
            if ($exchange->owner_id != $_SESSION['user_id']) {
                die("Access Denied.");
            }

            // 1. อัปเดตสถานะคำขอเป็น 'accepted'
            $exchange->status = 'accepted';
            if ($exchange->updateStatus()) {
                // 2. อัปเดตสถานะหนังสือเป็น 'exchanged'
                $book = new Book($db);
                $book->id = $exchange->book_id;
                $book->updateStatus('exchanged');
                
                // (Optional) Reject all other pending requests for this book
                // ...

                header('Location: index.php?controller=exchange&action=manageRequests');
            }
        }
    }

    // ปฏิเสธคำขอ
    public function rejectRequest($request_id) {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();

        $exchange = new Exchange($db);
        $exchange->id = $request_id;

        if ($exchange->readOne()) {
            // Security Check: ตรวจสอบว่าเป็นเจ้าของคำขอจริง
            if ($exchange->owner_id != $_SESSION['user_id']) {
                die("Access Denied.");
            }

            $exchange->status = 'rejected';
            if ($exchange->updateStatus()) {
                header('Location: index.php?controller=exchange&action=manageRequests');
            }
        }
    }
}
?>