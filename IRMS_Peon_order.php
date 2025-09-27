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

// Handle order status updates
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    if ($action === 'check') {
        $stmt = $mysqli->prepare("UPDATE peon_orders SET status='checked' WHERE id=?");
    } elseif ($action === 'confirm') {
        $stmt = $mysqli->prepare("UPDATE peon_orders SET status='confirmed' WHERE id=?");

        // 🔔 Insert notification for Procurement Team
        $note = $mysqli->prepare("INSERT INTO notifications (message, target_role) VALUES (?, 'pro_team')");
        $msg = "Order #$order_id has been confirmed by Peon.";
        $note->bind_param("s", $msg);
        $note->execute();
        $note->close();

    } elseif ($action === 'cancel') {
        $stmt = $mysqli->prepare("UPDATE peon_orders SET status='cancelled' WHERE id=?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: IRMS_Peon_order.php");
    exit();
}

// Fetch orders
$result = $mysqli->query("SELECT * FROM peon_orders ORDER BY sent_at DESC");
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Totals
$total_orders = count($orders);
$confirmed_orders = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
$cancelled_orders = count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peon Product Dashboard - IRMS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Peon_order.css">
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<header>
  <a href="IRMS_profile.php" class="home-btn"><i class="fas fa-home"></i> Home</a>
  <a href="IRMS_Peon_order.php" class="title-link">Delivery-Man Product Dashboard</a>
  <a href="IRMS_Peon_order.php?logout=true" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<div class="summary">
  <div class="total">Total Orders: <?= $total_orders ?></div>
  <div class="confirmed">Confirmed: <?= $confirmed_orders ?></div>
  <div class="cancelled">Cancelled: <?= $cancelled_orders ?></div>
</div>

<div class="order-box">
  <h3>Orders from Staff</h3>

  <div class="search-box">
    <input type="text" id="searchInput" placeholder="Search Product...">
  </div>

  <?php if (count($orders) > 0): ?>
  <table id="orderTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Product ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
      <tr class="status-<?= $o['status'] ?>">
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['product_id']) ?></td>
        <td><?= htmlspecialchars($o['product_name']) ?></td>
        <td>$<?= number_format($o['product_price'],2) ?></td>
        <td><?= ucfirst($o['status']) ?></td>
        <td>
          <form method="post" class="orderForm" data-id="<?= $o['id'] ?>">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <button type="button" class="check-btn" onclick="checkOrder(<?= $o['id'] ?>)">Check</button>
            <button type="submit" name="action" value="confirm" class="confirm-btn">Confirm</button>
            <button type="submit" name="action" value="cancel" class="cancel-btn">Cancel</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No orders yet.</p>
  <?php endif; ?>
</div>

<footer>© 2025 Inventory Requisition & Management System</footer>

<script>
// Simple search filter
document.getElementById("searchInput").addEventListener("keyup", function() {
  let filter = this.value.toUpperCase();
  let rows = document.querySelector("#orderTable tbody").rows;
  for (let i = 0; i < rows.length; i++) {
    let productName = rows[i].cells[2].textContent.toUpperCase();
    let productId = rows[i].cells[1].textContent.toUpperCase();
    rows[i].style.display = (productName.indexOf(filter) > -1 || productId.indexOf(filter) > -1) ? "" : "none";
  }
});

// Custom check prompt
function checkOrder(orderId) {
  let choice = confirm("Is this product available in your store?\n\nOK = Available (Confirm)\nCancel = Not Available (Cancel)");
  let form = document.querySelector(`form[data-id='${orderId}']`);
  if (choice) {
    form.querySelector("button.confirm-btn").click();
  } else {
    form.querySelector("button.cancel-btn").click();
  }
}
</script>

</body>
</html>
