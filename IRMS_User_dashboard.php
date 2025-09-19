<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "irms_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest application for current session (or latest submission if no session)
if (isset($_SESSION['applicant_info']['email'])) {
    $email = $_SESSION['applicant_info']['email'];
    $stmt = $conn->prepare("SELECT * FROM job_applications WHERE email=? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    // fallback: show latest record
    $result = $conn->query("SELECT * FROM job_applications ORDER BY id DESC LIMIT 1");
    $user = $result->fetch_assoc();
}

// Default values if nothing found
$name   = $user['fullname'] ?? 'Guest';
$email  = $user['email'] ?? 'Not provided';
$phone  = $user['phone'] ?? 'Not provided';
$gender = $user['gender'] ?? 'Not provided';
$role   = $user['position'] ?? 'Not selected';
$cv_file = $user['cv_file'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>User Dashboard - IRMS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="IRMS_User_dashboard.css" type="text/css">
</head>
<body>

  <header class="header">
    <h1>IR<span>MS</span> User Dashboard</h1>
    <div class="nav-buttons">
      <a href="IRMS_profile.php" onclick="<?php session_unset(); session_destroy(); ?>"><i class="fas fa-times"></i> Close</a>
    </div>
  </header>

  <div class="container">
    <!-- Welcome Notification -->
    <div class="welcome">✅ Welcome here, <?= htmlspecialchars($name) ?>!</div>

    <!-- User Info Table -->
    <table>
      <thead>
        <tr><th>Field</th><th>Information</th></tr>
      </thead>
      <tbody>
        <tr><td><i class="fas fa-user"></i> Name</td><td><?= htmlspecialchars($name) ?></td></tr>
        <tr><td><i class="fas fa-envelope"></i> Email</td><td><?= htmlspecialchars($email) ?></td></tr>
        <tr><td><i class="fas fa-phone"></i> Phone</td><td><?= htmlspecialchars($phone) ?></td></tr>
        <tr><td><i class="fas fa-venus-mars"></i> Gender</td><td><?= htmlspecialchars($gender) ?></td></tr>
        <tr><td><i class="fas fa-user-tag"></i> Role</td><td><?= htmlspecialchars($role) ?></td></tr>
        <tr><td><i class="fas fa-file"></i> CV</td>
            <td>
              <?php if($cv_file): ?>
                <a href="uploads/<?= htmlspecialchars($cv_file) ?>" target="_blank">Download CV</a>
              <?php else: ?>
                Not uploaded
              <?php endif; ?>
            </td>
        </tr>
      </tbody>
    </table>
  </div>

  <footer style="margin-top:20px; text-align:center; background:#2c3e50; color:white; padding:12px;">
    &copy; 2025 Inventory Requisition & Management System
  </footer>

</body>
</html>
