<?php
class StaffProductModel
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "irms_db");
        if ($this->mysqli->connect_error) {
            die("Database connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function getAllProducts(): array
    {
        $result = $this->mysqli->query("SELECT * FROM staff_products ORDER BY sent_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id): ?array
    {
        $stmt = $this->mysqli->prepare(
            "SELECT product_id, product_name, product_price FROM staff_products WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($prod_id, $prod_name, $prod_price);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            $product = [
                "product_id" => $prod_id,
                "product_name" => $prod_name,
                "product_price" => $prod_price
            ];
        } else {
            $product = null;
        }

        $stmt->close();
        return $product;
    }

    public function insertPeonOrder($prod_id, $prod_name, $prod_price): bool
    {
        $insert = $this->mysqli->prepare(
            "INSERT INTO peon_orders (product_id, product_name, product_price) VALUES (?, ?, ?)"
        );
        $insert->bind_param("ssd", $prod_id, $prod_name, $prod_price);
        $success = $insert->execute();
        $insert->close();

        return $success;
    }
}
