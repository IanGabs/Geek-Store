<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'tecnygeek';
    private $conn;
    
    public function __construct() {
        $this->connect();
        $this->startSession();
    }
    
    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }
    }
    
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>