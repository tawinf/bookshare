<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'book_share_db';
    private $username = 'root'; // <-- เปลี่ยนเป็น username ของคุณ
    private $password = '';     // <-- เปลี่ยนเป็น password ของคุณ
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('set names utf8');
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>