<?php
// app/models/JobApplication.php
class JobApplication {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO job_applications 
            (name, email, phone, gender, position, cv_file, apply_date) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "ssssss",
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['gender'],
            $data['position'],
            $data['cv_file']
        );

        return $stmt->execute();
    }
}
