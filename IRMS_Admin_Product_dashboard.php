<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: IRMS_Admin_signup.php");
    exit();
}

// DB connection
$mysqli = new mysqli("localhost", "root", "", "irms_db");
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Handle Add Product
if (isset($_POST['add_product'])) {
    $pid = $_POST['product_id'];
    $pname = $_POST['product_name'];
    $pprice = $_POST['product_price'];

    $stmt = $mysqli->prepare("INSERT INTO products (product_id, product_name, product_price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $pid, $pname, $pprice);
    $stmt->execute();
    $stmt->close();

    header("Location: IRMS_Admin_Product_dashboard.php");
    exit();
}

// Handle Edit Product
if (isset($_POST['edit_product'])) {
    $pid = $_POST['product_id'];
    $pname = $_POST['product_name'];
    $pprice = $_POST['product_price'];
    $id = $_POST['id'];

    $stmt = $mysqli->prepare("UPDATE products SET product_id=?, product_name=?, product_price=? WHERE id=?");
    $stmt->bind_param("ssdi", $pid, $pname, $pprice, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: IRMS_Admin_Product_dashboard.php");
    exit();
}

// Handle Delete Product
if (isset($_POST['delete_product'])) {
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: IRMS_Admin_Product_dashboard.php");
    exit();
}

// Handle Send to Staff
if (isset($_POST['send_to_staff']) && isset($_POST['selected_products'])) {
    foreach ($_POST['selected_products'] as $pid) {
        $stmt = $mysqli->prepare("SELECT product_id, product_name, product_price FROM products WHERE id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($prod_id, $prod_name, $prod_price);
        $stmt->fetch();
        $stmt->close();

        $insert = $mysqli->prepare("INSERT INTO staff_products (product_id, product_name, product_price) VALUES (?, ?, ?)");
        $insert->bind_param("ssd", $prod_id, $prod_name, $prod_price);
        $insert->execute();
        $insert->close();
    }
    header("Location: IRMS_Staff_Product_dashboard.php");
    exit();
}

// Fetch products
$result = $mysqli->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);

// Handle logout (redirect to IRMS_Access_system.php)
if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    header("Location: IRMS_Access_system.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Product Dashboard - IRMS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="IRMS_Admin_Product_dashboard.css?v=4">
</head>
<body>

<header>
  <!-- Home button -->
  <a href="IRMS_Admin_Product_dashboard.php" class="home-btn"><i class="fas fa-home"></i> Home</a>

  <!-- Dashboard link (click to refresh) -->
  <a href="IRMS_Admin_Product_dashboard.php" class="dashboard-link">
    Admin Product Dashboard - Logged in as: <?= htmlspecialchars($_SESSION['admin_email']) ?>
  </a>

  <!-- Logout button -->
  <form method="post" class="logout-form">
    <button type="submit" name="logout" class="logout-btn">
      Logout <i class="fas fa-sign-out-alt"></i>
    </button>
  </form>
</header>

<div class="main">
  <div class="actions">
    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search by Product Name or ID">
      <button class="btn search-btn" onclick="validateSearch()">Search</button>
    </div>
    <button class="add-btn" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Add Product</button>
  </div>

  <form method="post">
    <div class="table-container">
      <table id="productTable" class="product-table">
        <thead>
          <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
          <tr>
            <td><input type="checkbox" name="selected_products[]" value="<?= $p['id'] ?>"></td>
            <td><?= htmlspecialchars($p['product_id']) ?></td>
            <td><?= htmlspecialchars($p['product_name']) ?></td>
            <td>$<?= number_format($p['product_price'], 2) ?></td>
            <td>
              <button class="action-btn edit-btn" type="button"
                onclick="openEditModal(<?= $p['id'] ?>, '<?= $p['product_id'] ?>', '<?= htmlspecialchars($p['product_name'], ENT_QUOTES) ?>', <?= $p['product_price'] ?>)">
                Edit
              </button>
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" name="delete_product" class="action-btn delete-btn">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <button type="submit" name="send_to_staff" class="global-send-btn">Send to Staff</button>
  </form>
</div>

<footer>
  © 2025 Inventory Requisition & Management System
</footer>

<!-- Add Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <h2>Add Product</h2>
    <form method="post">
      <input type="text" name="product_id" placeholder="Enter Product ID" required>
      <input type="text" name="product_name" placeholder="Enter Product Name" required>
      <input type="number" step="0.01" name="product_price" placeholder="Enter Product Price" required>
      <div class="modal-actions">
        <button type="submit" name="add_product" class="save-btn">Save</button>
        <button type="button" class="close-btn" onclick="closeAddModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h2>Edit Product</h2>
    <form method="post">
      <input type="hidden" name="id" id="editId">
      <input type="text" name="product_id" id="editProductId" required>
      <input type="text" name="product_name" id="editProductName" required>
      <input type="number" step="0.01" name="product_price" id="editProductPrice" required>
      <div class="modal-actions">
        <button type="submit" name="edit_product" class="save-btn">Update</button>
        <button type="button" class="close-btn" onclick="closeEditModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
/* Search */
function validateSearch() {
  let input = document.getElementById("searchInput").value.trim();
  if (input === "") {
    alert("Please enter a Product ID or Name to search.");
    return;
  }
  filterTable(input);
}
function filterTable(query) {
  let filter = query.toUpperCase();
  let table = document.getElementById("productTable");
  let tr = table.getElementsByTagName("tr");
  for (let i = 1; i < tr.length; i++) {
    let tdId = tr[i].getElementsByTagName("td")[1];
    let tdName = tr[i].getElementsByTagName("td")[2];
    if (tdId || tdName) {
      let idValue = tdId.textContent || tdId.innerText;
      let nameValue = tdName.textContent || tdName.innerText;
      if (idValue.toUpperCase().indexOf(filter) > -1 || nameValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

/* Add Modal */
function openAddModal() { document.getElementById("addModal").style.display = "flex"; }
function closeAddModal() { document.getElementById("addModal").style.display = "none"; }

/* Edit Modal */
function openEditModal(id, pid, pname, price) {
  document.getElementById("editId").value = id;
  document.getElementById("editProductId").value = pid;
  document.getElementById("editProductName").value = pname;
  document.getElementById("editProductPrice").value = price;
  document.getElementById("editModal").style.display = "flex";
}
function closeEditModal() { document.getElementById("editModal").style.display = "none"; }
</script>

</body>
</html>
