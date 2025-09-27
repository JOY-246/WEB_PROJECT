<?php
class User {
    private $conn;
    private $table;

    public function __construct($db, $roleTable) {
        $this->conn = $db;
        $this->table = $roleTable;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed);
        return $stmt->execute();
    }
}
