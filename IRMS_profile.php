<?php
// Start session (valid for 5 minutes)
session_set_cookie_params(300); // 300 seconds = 5 minutes
session_start();

// End any previous session data
session_unset();
session_destroy();
session_start(); // Start a fresh session

// Check if "Apply for Job" button was clicked
if (isset($_GET['apply']) && $_GET['apply'] === 'true') {
    $_SESSION['apply_job'] = true;  // Start apply session
    header("Location: IRMS_Apply_job.php");  // Redirect directly
    exit();
}

// Check if "Login" button was clicked (optional redirect)
if (isset($_GET['login']) && $_GET['login'] === 'true') {
    header("Location: IRMS_Access_system.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>IRMS - Inventory Requisition & Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_profile.css" type="text/css">

</head>
<body>

<!-- Header -->
<div class="header">
  <h1>IR<span>MS</span></h1>
  <div class="nav">
    <a href="?login=true"><i class="fas fa-sign-in-alt"></i> Login</a>
  </div>
</div>

<!-- Hero -->
<div class="hero">
  <h2>Inventory Requisition & Management System</h2>
  <p>Streamline your inventory management, requisition processes, and delivery tracking with our comprehensive solution.</p>
</div>

<!-- Features -->
<div class="features">
  <h2>System Features</h2>
  <div class="features-row">
    <div class="feature-card">
      <i class="fas fa-boxes"></i>
      <h3>Inventory Management</h3>
      <p>Track and manage inventory items in real-time with detailed reporting and analytics.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-clipboard-check"></i>
      <h3>Requisition Processing</h3>
      <p>Automate requisition workflows with easy approvals and tracking.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-truck"></i>
      <h3>Delivery Tracking</h3>
      <p>Monitor deliveries in real-time and get notifications for updates.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-user-plus"></i>
      <h3>Staff Management</h3>
      <p>Manage staff roles, permissions, and accounts with powerful admin tools.</p>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Inventory Requisition & Management System</p>

  <!-- Apply for Job Button -->
  <a href="?apply=true" class="btn-footer-new">
    <i class="fas fa-briefcase"></i> Apply for Job
  </a>

  <ul class="footer-links">
    <li><a href="about.html">About</a></li>|
    <li><a href="privacy.html">Privacy</a></li>|
    <li><a href="terms.html">Terms</a></li>|
    <li><a href="contact.html">Contact</a></li>
  </ul>
</div>

</body>
</html>
