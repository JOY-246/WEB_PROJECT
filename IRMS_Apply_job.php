<?php 
session_set_cookie_params(300); // 5 minutes
session_start();

// Redirect if session not started from profile page
if (!isset($_SESSION['apply_job']) || $_SESSION['apply_job'] !== true) {
    header("Location: IRMS_profile.php");
    exit();
}

// Track session start for auto-expire
if (!isset($_SESSION['apply_start'])) {
    $_SESSION['apply_start'] = time();
} else {
    $elapsed = time() - $_SESSION['apply_start'];
    if ($elapsed > 300) { // 5 minutes
        session_unset();
        session_destroy();
        header("Location: IRMS_profile.php");
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save applicant info in a temporary variable
    $applicant_info = [
        'fullname' => $_POST['fullname'] ?? '',
        'email'    => $_POST['email'] ?? '',
        'phone'    => $_POST['phone'] ?? '',
        'gender'   => $_POST['gender'] ?? ''
    ];

    // End current session
    session_unset();
    session_destroy();

    // Start a new session for CV submission
    session_start();
    $_SESSION['applicant_info'] = $applicant_info;

    // Redirect to CV submit page
    header("Location: IRMS_CV_submit.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IRMS - Apply for Job</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Apply_job.css" type="text/css">
</head>
<body>

<!-- Header -->
<div class="header">
  <h1>IR<span>MS</span></h1>
  <div class="nav">
    <a href="IRMS_profile.php"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
</div>

<!-- Hero -->
<div class="hero">
  <h2>Apply for Your Career at IRMS</h2>
  <p>Join our team and contribute to the future of Inventory Requisition & Management System.</p>
</div>

<!-- Application Form -->
<div class="form-section">
  <h3><i class="fas fa-briefcase"></i> Job Application Form</h3>
  <form id="jobForm" method="post" onsubmit="return validateForm()">
    
    <div class="form-group">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" placeholder="Enter your full name">
      <div class="error" id="errorName">Please enter your full name.</div>
    </div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="Enter your email">
      <div class="error" id="errorEmail">Please enter a valid email address.</div>
    </div>

    <div class="form-group">
      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
      <div class="error" id="errorPhone">Please enter a valid phone number (10-15 digits).</div>
    </div>

    <!-- Gender Selection -->
    <div class="form-group">
      <label>Gender</label>
      <div class="gender-options">
        <input type="radio" id="male" name="gender" value="Male">
        <label for="male" class="gender-card"><i class="fas fa-mars"></i> Male</label>

        <input type="radio" id="female" name="gender" value="Female">
        <label for="female" class="gender-card"><i class="fas fa-venus"></i> Female</label>
      </div>
      <div class="error" id="errorGender">Please select your gender.</div>
    </div>

    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Application</button>
  </form>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Inventory Requisition & Management System</p>
</div>

<script>
function validateForm() {
    let valid = true;

    const name = document.getElementById("fullname").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const genderSelected = document.querySelector('input[name="gender"]:checked');

    if (name === "") {
        document.getElementById("errorName").style.display = "block";
        valid = false;
    } else { document.getElementById("errorName").style.display = "none"; }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        document.getElementById("errorEmail").style.display = "block";
        valid = false;
    } else { document.getElementById("errorEmail").style.display = "none"; }

    const phonePattern = /^[0-9]{10,15}$/;
    if (!phonePattern.test(phone)) {
        document.getElementById("errorPhone").style.display = "block";
        valid = false;
    } else { document.getElementById("errorPhone").style.display = "none"; }

    if (!genderSelected) {
        document.getElementById("errorGender").style.display = "block";
        valid = false;
    } else { document.getElementById("errorGender").style.display = "none"; }

    return valid;
}
</script>
</body>
</html>
