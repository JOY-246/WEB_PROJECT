<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";       // change if needed
$pass = "";           // change if needed
$db   = "IRMS_db";    // make sure DB exists

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Message
$errorMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($email) || empty($password)) {
        $errorMsg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $errorMsg = "Password must be at least 6 characters long.";
    } else {
        // Check if Super Admin already exists
        $check = $conn->query("SELECT * FROM super_admin LIMIT 1");

        if ($check->num_rows > 0) {
            // Super Admin exists → verify login
            $admin = $check->fetch_assoc();
            if (password_verify($password, $admin['password']) && $email === $admin['email']) {
                // Credentials correct → set session & redirect
                $_SESSION['admin_email'] = $email;
                header("Location: IRMS_Admin_Product_dashboard.php");
                exit();
            } else {
                $errorMsg = "Invalid email or password.";
            }
        } else {
            // No Super Admin → create one
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO super_admin (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['admin_email'] = $email;
                header("Location: IRMS_Admin_Product_dashboard.php");
                exit();
            } else {
                $errorMsg = "Error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IRMS - Super Admin Sign Up / Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Admin_signup.css">
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="nav">
    <a href="IRMS_Access_system.php">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>
  <h1>IR<span>MS</span></h1>
</div>

<!-- Sign Up / Login Form -->
<div class="container">
  <div class="form-section">
    <h3><i class="fas fa-user-shield"></i> Super Admin Sign Up / Login</h3>

    <?php if ($errorMsg): ?>
      <div class="error-box"><?= $errorMsg ?></div>
    <?php endif; ?>

    <form id="adminForm" action="" method="post" onsubmit="return validateForm()">

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <div class="error" id="errorEmail">Please enter a valid email.</div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
        <div class="error" id="errorPassword">Password must be at least 6 characters.</div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="fas fa-user-plus"></i> Sign Up / Login
      </button>
    </form>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Inventory Requisition & Management System</p>
</div>

<script>
// Client-side validation
function validateForm() {
    let valid = true;

    const email = document.getElementById("email").value.trim();
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/i;
    if (!emailPattern.test(email)) {
        document.getElementById("errorEmail").style.display = "block";
        valid = false;
    } else {
        document.getElementById("errorEmail").style.display = "none";
    }

    const password = document.getElementById("password").value;
    if (password.length < 6) {
        document.getElementById("errorPassword").style.display = "block";
        valid = false;
    } else {
        document.getElementById("errorPassword").style.display = "none";
    }

    return valid;
}
</script>

</body>
</html>
