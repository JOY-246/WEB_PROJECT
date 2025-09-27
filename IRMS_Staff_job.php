<?php 
// Start session with 10-minute lifetime
session_set_cookie_params(600);
session_start();

// 🔹 Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: IRMS_Access_system.php");
    exit();
}

// Redirect if no role set
if (!isset($_SESSION['role'])) {
    session_unset();
    session_destroy();
    header("Location: IRMS_Access_system.php");
    exit();
}

// Redirect Manager and DeliveryMan directly to User Dashboard
if (isset($_SESSION['role']) && ($_SESSION['role'] === "Manager" || $_SESSION['role'] === "DeliveryMan")) {
    session_unset();
    session_destroy();
    header("Location: IRMS_User_dashboard.php");
    exit();
}

// Fetch user info from session (from CV submission)
$role = $_SESSION['role'] ?? '';
$email = $_SESSION['email'] ?? '';

$fullname = 'Unknown';
$gender = 'Unknown';
$phone = 'Unknown';

// Fetch user info from database using email
if ($email) {
    $conn = new mysqli("localhost", "root", "", "irms_db");
    if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("SELECT name, phone, gender FROM job_applications WHERE email=? ORDER BY apply_date DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($dbname, $dbPhone, $dbGender);
    if ($stmt->fetch()) {
        $name = $dbname;
        $phone = $dbPhone;
        $gender = $dbGender;
    }
    $stmt->close();

    // Fetch available skills
    $availableSkills = [];
    $result = $conn->query("SELECT skill_name FROM skills ORDER BY skill_name");
    while ($row = $result->fetch_assoc()) {
        $availableSkills[] = $row['skill_name'];
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Job - IRMS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Staff_job.css" type="text/css">
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<header>
  <?= htmlspecialchars($role) ?> Skills Selection

  <!--  Logout button on header corner -->
  <a href="?logout=true" 
     class="logout-btn" 
     style="float:right; margin-right:15px; color:#fff; background:#e74c3c; padding:6px 12px; border-radius:5px; text-decoration:none;">
     <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</header>

<div class="main">
  <h2>Welcome, <?= htmlspecialchars($fullname) ?></h2>
  <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
  <p><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></p>
  <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>

  <?php if($role === "Staff"): ?>
    <form id="skillsForm" method="post" onsubmit="return validateForm()">
      <div class="cards" id="skillsContainer">
        <?php foreach ($availableSkills as $skill): ?>
          <div class="card" data-skill="<?= htmlspecialchars($skill) ?>">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($skill) ?>
          </div>
        <?php endforeach; ?>
      </div>

      <button type="button" class="more-btn" onclick="showCustomInput()"><i class="fas fa-plus"></i> More</button>

      <div class="custom-skill" id="customSkillBox" style="display:none;">
        <input type="text" id="customSkillInput" placeholder="Enter your skill">
        <button type="button" onclick="addCustomSkill()">Add</button>
      </div>

      <div class="experience-container">
        <label for="experience">Experience (Years):</label>
        <input type="number" id="experience" name="experience" min="0">
      </div>

      <div class="error" id="errorSkills">Please select at least 2 skills.</div>
      <div class="error" id="errorExp">Please enter a valid experience.</div>

      <input type="hidden" name="skills" id="hiddenSkills">
      <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Skills</button>
    </form>
  <?php endif; ?>
</div>

<script>
// JS for skills selection
let selectedSkills = [];

document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    card.classList.toggle('selected');
    const skill = card.getAttribute('data-skill');
    if(card.classList.contains('selected')){
      selectedSkills.push(skill);
    } else {
      selectedSkills = selectedSkills.filter(s => s !== skill);
    }
  });
});

function showCustomInput() {
  document.getElementById('customSkillBox').style.display = 'flex';
}

function addCustomSkill() {
  const skillInput = document.getElementById('customSkillInput').value.trim();
  if(skillInput !== ""){
    const newCard = document.createElement('div');
    newCard.classList.add('card');
    newCard.setAttribute('data-skill', skillInput);
    newCard.innerHTML = `<i class="fas fa-star"></i> ${skillInput}`;
    document.getElementById('skillsContainer').appendChild(newCard);

    newCard.addEventListener('click', () => {
      newCard.classList.toggle('selected');
      const skill = newCard.getAttribute('data-skill');
      if(newCard.classList.contains('selected')){
        selectedSkills.push(skill);
      } else {
        selectedSkills = selectedSkills.filter(s => s !== skill);
      }
    });

    document.getElementById('customSkillInput').value = "";
    document.getElementById('customSkillBox').style.display = 'none';
    newCard.click(); // auto-select
  }
}

function validateForm() {
  const exp = document.getElementById('experience').value;
  let valid = true;

  if(selectedSkills.length < 2){
    document.getElementById('errorSkills').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('errorSkills').style.display = 'none';
  }

  if(exp === "" || exp < 0){
    document.getElementById('errorExp').style.display = 'block';
    valid = false;
  } else {
    document.getElementById('errorExp').style.display = 'none';
  }

  if(valid){
    document.getElementById('hiddenSkills').value = selectedSkills.join(',');

    // Redirect to User Dashboard after submit
    window.location.href = 'IRMS_User_dashboard.php';
    return false; // prevent actual form submission
  }
  return false;
}
</script>
</body>
</html>
