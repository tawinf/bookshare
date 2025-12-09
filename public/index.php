<?php
session_start(); // <--- เพิ่มบรรทัดนี้ไว้บนสุด

include_once __DIR__ . '/../controllers/HomeController.php';
include_once __DIR__ . '/../controllers/AuthController.php';
include_once __DIR__ . '/../controllers/UserController.php';
include_once __DIR__ . '/../controllers/BookController.php';
include_once __DIR__ . '/../controllers/ExchangeController.php';
include_once __DIR__ . '/../controllers/DashboardController.php';





$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController'; // <--- เพิ่ม ระบบ Routing แบบง่าย

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;


$controller = new $controllerName();

// ตรวจสอบว่าเมธอดมีอยู่จริงหรือไม่ก่อนเรียกใช้
if (method_exists($controller, $action)) {
    if ($id) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }
} else {
    die('Action not found!');
}
?>