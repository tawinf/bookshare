<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Book.php';
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../models/Exchange.php';

class DashboardController {

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die('Access Denied.');
        }
    }

    public function index() {
        $this->checkAdmin();
        
        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $userModel = new User($db);
        $exchangeModel = new Exchange($db);

        // 1. ดึงข้อมูลสำหรับ KPI Cards
        $totalBooks = $bookModel->countAll();
        $totalUsers = $userModel->countAll();
        $pendingRequests = $exchangeModel->countByStatus('pending');

        // 2. ดึงข้อมูลสำหรับกราฟสถานะหนังสือ
        $bookStatusData = $bookModel->getStatusBreakdown();
        $bookStatusLabels = [];
        $bookStatusValues = [];
        foreach ($bookStatusData as $data) {
            $bookStatusLabels[] = ucfirst($data['status']); // e.g., Available, Exchanged
            $bookStatusValues[] = $data['count'];
        }
        
        // 3. ดึงข้อมูลสำหรับกราฟคำขอรายวัน
        $dailyRequestsData = $exchangeModel->getNewRequestsLast7Days();
        $dailyRequestsLabels = [];
        $dailyRequestsValues = [];
        foreach ($dailyRequestsData as $data) {
            $dailyRequestsLabels[] = $data['request_date'];
            $dailyRequestsValues[] = $data['count'];
        }

        // 4. ดึงข้อมูลสำหรับกราฟสถานะคำขอแลกเปลี่ยน
        $exchangeStatusData = $exchangeModel->getStatusBreakdown();
        $exchangeStatusLabels = [];
        $exchangeStatusValues = [];
        foreach ($exchangeStatusData as $data) {
            $exchangeStatusLabels[] = ucfirst($data['status']); // e.g., Pending, Accepted, Rejected
            $exchangeStatusValues[] = $data['count'];
        }

        // ส่งข้อมูลทั้งหมดไปยัง View
        include_once __DIR__ . '/../views/dashboard/index.php';
    }

    /**
     * เมธอดใหม่สำหรับแสดงคำขอแลกเปลี่ยนทั้งหมด
     */
    public function allRequests() {
        $this->checkAdmin(); // ตรวจสอบสิทธิ์ Admin
        
        $database = new Database();
        $db = $database->getConnection();
        
        $exchangeModel = new Exchange($db);
        $stmt = $exchangeModel->readAll(); // เรียกใช้เมธอดใหม่

        // ส่งข้อมูลไปแสดงผลที่ View ใหม่
        include_once __DIR__ . '/../views/dashboard/all_requests.php';
    }
}
?>