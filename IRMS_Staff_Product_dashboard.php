<?php
session_start();

// DB connection
$mysqli = new mysqli("localhost", "root", "", "irms_db");
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// 🔹 Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: IRMS_Access_system.php");
    exit();
}

// Fetch staff products ordered by newest first
$result = $mysqli->query("SELECT * FROM staff_products ORDER BY sent_at DESC");
$staff_products = $result->fetch_all(MYSQLI_ASSOC);

// Determine the latest sent_at timestamp
$latest_sent = $staff_products[0]['sent_at'] ?? null;

// Handle Send to Delivery Man
if (isset($_POST['send_to_delivery']) && isset($_POST['selected_products'])) {
    foreach ($_POST['selected_products'] as $pid) {
        // Fetch product info
        $stmt = $mysqli->prepare("SELECT product_id, product_name, product_price FROM staff_products WHERE id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($prod_id, $prod_name, $prod_price);
        $stmt->fetch();
        $stmt->close();

        // Insert into peon_orders
        $insert = $mysqli->prepare("INSERT INTO peon_orders (product_id, product_name, product_price) VALUES (?, ?, ?)");
        $insert->bind_param("ssd", $prod_id, $prod_name, $prod_price);
        $insert->execute();
        $insert->close();
    }

    header("Location: IRMS_Peon_order.php");
    exit();
}

// Handle Back
if (isset($_POST['back'])) {
    header("Location: IRMS_Access_system.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Product Dashboard - IRMS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="IRMS_Staff_Product_dashboard.css?v=11">
</head>
<body>

<header>
  <!-- Home button (left) -->
  <a href="IRMS_profile.php" class="home-btn"><i class="fas fa-home"></i> Home</a>

  <!-- Title in center as underlined hyperlink -->
  <a href="IRMS_Staff_Product_dashboard.php?refresh=<?= time(); ?>" class="title-link">Staff_Product_Dashboard</a>

  <!-- Logout button (right) -->
  <a href="IRMS_Staff_Product_dashboard.php?logout=true" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<div class="main">
  <form method="post">
    <div class="table-container">
      <table class="product-table">
        <thead>
          <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Sent At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($staff_products as $p): ?>
          <?php $row_class = ($p['sent_at'] === $latest_sent) ? 'recent' : ''; ?>
          <tr class="<?= $row_class ?>">
            <td><input type="checkbox" name="selected_products[]" value="<?= $p['id'] ?>"></td>
            <td><?= htmlspecialchars($p['product_id']) ?></td>
            <td><?= htmlspecialchars($p['product_name']) ?></td>
            <td>$<?= number_format($p['product_price'], 2) ?></td>
            <td><?= $p['sent_at'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Send button near the bottom -->
    <div class="send-btn-container">
      <button type="submit" name="send_to_delivery" class="global-send-btn">
        <i class="fas fa-truck"></i> Send to Delivery Man
      </button>
    </div>
  </form>
</div>

<footer>
  © 2025 Inventory Requisition & Management System
</footer>

</body>
</html>
