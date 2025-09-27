<?php
// Session cookie ends when browser closes
ini_set('session.cookie_lifetime', 0);
session_start();

// Auto-logout timeout in seconds
$timeout_duration = 10; // 10 seconds as requested

// Check session activity
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $elapsed = time() - $_SESSION['LAST_ACTIVITY'];
    if ($elapsed > $timeout_duration) {
        // Session expired -> destroy it
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        session_start();
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

// If already logged in, redirect to dashboard
if (isset($_SESSION['role'])) {
    $map = [
        "Staff" => "IRMS_Staff_job.php",
        "Deliveryman" => "IRMS_Peon_job.php",
        "Procurement" => "IRMS_Procurement_job.php",
        "Manager" => "IRMS_Manager_job.php",
        "Admin" => "IRMS_Admin_Product_dashboard.php"
    ];
    $role = $_SESSION['role'];
    if (isset($map[$role])) {
        header("Location: " . $map[$role]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role - IRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="IRMS_Access_system.css">
    <link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>
    <header>
        <a href="IRMS_profile.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
           <a href="IRMS_Access_system.php" style="color:white;">
  Select Login Role
</a>
    </header>
    <div class="main">
        <div class="cards">
            <!-- Admin Login -->
            <a href="IRMS_Admin_signup.php" class="card">
                <i class="fas fa-user-shield"></i>
                Admin Login
            </a>
            <!-- Staff Login -->
            <a href="IRMS_Login.php?role=Staff" class="card staff">
                <i class="fas fa-user-tie"></i>
                Staff Login
            </a>
            <!-- Procurement Team Login -->
            <a href="IRMS_Login.php?role=Procurement" class="card procurement">
                <i class="fas fa-people-carry"></i>
                Procurement Team Login
            </a>
            <!-- Manager Login -->
            <a href="IRMS_Login.php?role=Manager" class="card manager">
                <i class="fas fa-user-cog"></i>
                Manager Login
            </a>
            <!-- Deliveryman Login -->
            <a href="IRMS_Login.php?role=Deliveryman" class="card delivery">
                <i class="fas fa-truck"></i>
                Deliveryman Login
            </a>
        </div>
    </div>
    <footer>
        <div class="footer-section">
            <strong>IRMS</strong>
            <p>Manage inventory and requests efficiently</p>
        </div>
        <div class="footer-section">
            <strong>Contact Info</strong>
            <p>Email: irms@gmail.com</p>
            <p>Phone: (880) 123-456-789</p>
            <p>Address: 123 Jamuna Future Park, Dhaka, Bangladesh</p>
        </div>
    </footer>
</body>
</html>
