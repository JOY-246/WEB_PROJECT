<?php
session_set_cookie_params(300); // 5 minutes
session_start();

// Check applicant info from session
if (!isset($_SESSION['applicant_info'])) {
    header("Location: IRMS_Apply_job.php");
    exit();
}

$errors = [];
$conn = new mysqli("localhost", "root", "", "irms_db");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $position = $_POST['position'] ?? '';
    if (!$position) $errors[] = "Select a position.";

    // CV upload validation
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Upload CV.";
    } else {
        $cv = $_FILES['cv'];
        $ext = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'doc', 'docx'])) $errors[] = "Invalid CV format.";
        if ($cv['size'] > 5 * 1024 * 1024) $errors[] = "CV too large (>5MB).";
    }

    if (empty($errors)) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $filename = time() . '_' . basename($cv['name']);
        move_uploaded_file($cv['tmp_name'], $upload_dir . $filename);

        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO job_applications (name,email,phone,gender,position,cv_file,apply_date) VALUES (?,?,?,?,?, ?,NOW())");
        $stmt->bind_param(
            "ssssss",
            $_SESSION['applicant_info']['fullname'], 
            $_SESSION['applicant_info']['email'],
            $_SESSION['applicant_info']['phone'],
            $_SESSION['applicant_info']['gender'],
            $position,
            $filename
        );

        if ($stmt->execute()) {
            $_SESSION['role'] = $position;
            $_SESSION['email'] = $_SESSION['applicant_info']['email'];

            unset($_SESSION['applicant_info']);

            // Redirect based on role
            if ($position === "Staff") header("Location: IRMS_Staff_job.php");
            else header("Location: IRMS_User_dashboard.php");
            exit();
        } else $errors[] = $stmt->error;
    }
}

// Handle back button
if (isset($_GET['back'])) {
    session_unset();
    session_destroy();
    header("Location: IRMS_Apply_job.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CV Submission - IRMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_CV_submit.css">
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<header>
  <a href="IRMS_CV_submit.php" style="color:white;">CV Submission - IRMS</a>
</header>

<div class="main">
<?php
if (!empty($errors)) {
    echo '<div class="error-messages"><ul>';
    foreach ($errors as $error) echo "<li>$error</li>";
    echo '</ul></div>';
}
?>

<form id="cvForm" enctype="multipart/form-data" method="post">
    <h2 style="text-align:center; margin-bottom:20px;">Select Your Position</h2>

    <div class="cards">
        <div class="card" data-position="Staff"><i class="fas fa-user-tie"></i> Staff</div>
        <div class="card" data-position="Manager"><i class="fas fa-user"></i> Manager</div>
        <div class="card" data-position="DeliveryMan"><i class="fas fa-user-graduate"></i> Delivery Man</div>
    </div>
    <input type="hidden" name="position" id="positionInput" value="">
    <div class="error" id="errorPosition">Please select your position.</div>

    <div class="upload-container">
        <label for="cv">Upload Your CV (PDF/DOC/DOCX, Max 5MB)</label><br>
        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
        <div class="error" id="errorCV">Please upload a valid CV file (max 5MB).</div>
    </div>

    <div class="button-group">
        <a href="?back=true" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-finish"><i class="fas fa-check"></i> Finish</button>
    </div>
</form>
</div>

<script>
let selectedPosition = "";
const cards = document.querySelectorAll('.card');

cards.forEach(card => {
    card.addEventListener('click', () => {
        cards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedPosition = card.getAttribute('data-position');
        document.getElementById('positionInput').value = selectedPosition;
    });
});
</script>
</body>
</html>
