<?php
session_start();

// Redirect if not Manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Manager") {
    header("Location: IRMS_Access_system.php");
    exit();
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "irms_db");
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// Handle Approve/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'Approve' ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE job_applications SET status=? WHERE id=?");
    $stmt->bind_param("si", $action, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all applicants
$result = $conn->query("SELECT * FROM job_applications ORDER BY apply_date DESC");
$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>IRMS Applicants</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="IRMS_Applicants.css"/>
<link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<header class="header"> 
  <div class="nav-buttons"> 
    <a href="IRMS_Manager_dashboard.php"><i class="fas fa-arrow-left"></i> Back</a> 
  </div> 
  <h1>IR<span class="yellow">MS</span> Applicants</h1> 
</header>

<div class="wrap">
  <aside class="sidebar">
    <div class="option" onclick="show('list')"><i class="fas fa-users"></i> Applicant List</div>
    <div class="option" onclick="show('status')"><i class="fas fa-clipboard-check"></i> Application Status</div>
  </aside>

  <main class="main">
    <!-- Applicant List -->
    <section id="list" class="card view">
      <div class="content-header"><i class="fas fa-users"></i> Applicant List</div>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Skills</th>
            <th>Experience</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applicants as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['name']) ?></td>
            <td><?= htmlspecialchars($a['email']) ?></td>
            <td><?= htmlspecialchars($a['position']) ?></td>
            <td><?= $a['position'] === 'Staff' ? htmlspecialchars($a['skills']) : 'N/A' ?></td>
            <td><?= $a['position'] === 'Staff' ? htmlspecialchars($a['experience']) : 'N/A' ?></td>
            <td><?= htmlspecialchars($a['status']) ?></td>
            <td>
              <?php if ($a['status'] === 'Pending'): ?>
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" name="action" value="Approve" class="approve-btn"><i class="fas fa-check"></i> Approve</button>
              </form>
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" name="action" value="Reject" class="reject-btn"><i class="fas fa-times"></i> Reject</button>
              </form>
              <?php else: ?>
                <button disabled style="opacity:0.5;"><?= htmlspecialchars($a['status']) ?></button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Application Status -->
    <section id="status" class="card view" style="display:none;">
      <div class="content-header"><i class="fas fa-clipboard-check"></i> Application Status</div>
      <?php
      $pending = array_filter($applicants, fn($a)=>$a['status']==='Pending');
      $approved = array_filter($applicants, fn($a)=>$a['status']==='Approved');
      ?>
      <div class="status-counts">
        <div class="pending clickable" onclick="showList('Pending')">
          Pending List<br><span id="pendingCount"><?= count($pending) ?></span>
        </div>
        <div class="approved clickable" onclick="showList('Approved')">
          Approved List<br><span id="approvedCount"><?= count($approved) ?></span>
        </div>
      </div>

      <div id="statusList" style="margin-top:20px;"></div>
    </section>
  </main>
</div>

<footer>&copy; 2025 Inventory Requisition & Management System</footer>

<script>
const applicants = <?= json_encode($applicants) ?>;

function show(id){
  document.querySelectorAll('.view').forEach(s=> s.style.display='none');
  document.getElementById(id).style.display='block';
}

let currentList = null;
function showList(status){
  currentList = status;
  const container = document.getElementById('statusList');
  container.innerHTML = "";
  const filtered = applicants.filter(a => a.status === status);
  if(filtered.length===0){
    container.innerHTML="<p>No applicants.</p>";
    return;
  }
  const table = document.createElement('table');
  table.innerHTML = `
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Skills</th>
        <th>Experience</th>
      </tr>
    </thead>
    <tbody></tbody>
  `;
  const tbody = table.querySelector('tbody');
  filtered.forEach(a=>{
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${a.name}</td>
      <td>${a.email}</td>
      <td>${a.position}</td>
      <td>${a.position==='Staff'?a.skills:'N/A'}</td>
      <td>${a.position==='Staff'?a.experience:'N/A'}</td>
    `;
    tbody.appendChild(row);
  });
  container.appendChild(table);
}
</script>
</body>
</html>
