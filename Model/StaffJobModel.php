<?php
class StaffJobModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "irms_db");
        if ($this->conn->connect_error) {
            die("DB Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT name, phone, gender 
            FROM job_applications 
            WHERE email=? 
            ORDER BY apply_date DESC 
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($name, $phone, $gender);

        if ($stmt->fetch()) {
            $user = [
                "name" => $name,
                "phone" => $phone,
                "gender" => $gender
            ];
        } else {
            $user = null;
        }

        $stmt->close();
        return $user;
    }

    public function getAvailableSkills(): array
    {
        $skills = [];
        $result = $this->conn->query("SELECT skill_name FROM skills ORDER BY skill_name");
        while ($row = $result->fetch_assoc()) {
            $skills[] = $row['skill_name'];
        }
        return $skills;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
