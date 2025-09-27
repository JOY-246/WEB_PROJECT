<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "irms_db");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

// Detect role from URL
$role = isset($_GET['role']) ? $_GET['role'] : '';
$tableMap = [
    "Staff" => "staff",
    "Deliveryman" => "deliveryman",
    "Manager" => "manager",
    "Procurement" => "procurement",
    "Admin" => "admin"
];
if (!isset($tableMap[$role])) die("Invalid role.");
$table = $tableMap[$role];

$error = "";

// Auto-logout timeout in seconds
$timeout_duration = 10;

// Check session timeout
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
        session_unset();
        session_destroy();
        session_start();
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($_POST['action'] === "signup") {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists → login attempt
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;
                $_SESSION['LAST_ACTIVITY'] = time();

                // Normal redirection
                switch ($role) {
                    case "Staff": header("Location: IRMS_Staff_Product_dashboard.php"); exit;
                    case "Deliveryman": header("Location: IRMS_Peon_order.php"); exit;
                    case "Manager": header("Location: IRMS_Manager_dashboard.php"); exit;
                    case "Procurement": header("Location: IRMS_Pro_Team_dashboard.php"); exit;
                    case "Admin": header("Location: IRMS_Admin_dashboard.php"); exit;
                }
            } else {
                $error = "Incorrect password for existing account.";
            }
        } else {
            // User doesn't exist → create account
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO $table (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed);
            if ($stmt->execute()) {
                if ($role === "Procurement") {
                    // Special case → force logout and redirect to login page
                    session_unset();
                    session_destroy();
                    header("Location: IRMS_Login.php?role=Procurement");
                    exit;
                } else {
                    $_SESSION['role'] = $role;
                    $_SESSION['email'] = $email;
                    $_SESSION['LAST_ACTIVITY'] = time();

                    // Normal signup redirect
                    switch ($role) {
                        case "Staff": header("Location: IRMS_Staff_Product_dashboard.php"); exit;
                        case "Deliveryman": header("Location: IRMS_Peon_order.php"); exit;
                        case "Manager": header("Location: IRMS_Manager_dashboard[1].php"); exit;
                        case "Admin": header("Location: IRMS_Admin_dashboard.php"); exit;
                    }
                }
            } else {
                $error = "Signup failed. Try again.";
            }
        }
    }

    // Login portion
    if ($_POST['action'] === "login") {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;
                $_SESSION['LAST_ACTIVITY'] = time();
                switch ($role) {
                    case "Staff": header("Location: IRMS_Staff_job.php"); exit;
                    case "Deliveryman": header("Location: IRMS_Peon_job.php"); exit;
                    case "Manager": header("Location: IRMS_Manager_job.php"); exit;
                    case "Procurement": header("Location: IRMS_Pro_Team_dashboard.php"); exit;
                    case "Admin": header("Location: IRMS_Admin_dashboard.php"); exit;
                }
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found. Please signup.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IRMS - <?= htmlspecialchars($role) ?> Authentication</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="IRMS_Login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>
<div class="header">
  <div class="nav">
    <a href="IRMS_Access_system.php"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  <h1>
    <a href="IRMS_Login.php?role=Staff" style="color:white;">IR<span>MS</span></h1>
</div>

<div class="container">
  <div class="form-section">
    <h3><i class="fas fa-user"></i> <?= htmlspecialchars($role) ?> Authentication</h3>
    <?php if($error) echo '<p class="error">'.$error.'</p>'; ?>
    <form method="post">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required minlength="6">
      </div>
      <div class="form-buttons">
        <button type="submit" name="action" value="login" disabled><i class="fas fa-sign-in-alt"></i> Login</button>
        <button type="submit" name="action" value="signup"><i class="fas fa-user-plus"></i> Signup / Login</button>
      </div>
    </form>
  </div>
</div>

<div class="footer">
  <p>&copy; 2025 Inventory Requisition & Management System</p>
</div>
</body>
</html>
