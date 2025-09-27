<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IRMS Pro. Team Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="IRMS_Pro_Team_dashboard.css" type="text/css"/>
  <link rel="icon" type="image/png" href="imgs/favicon.png">
</head>
<body>

<header class="header">
  <div class="nav-buttons">
    <a href="IRMS_Access_system.php" id="backBtn" onclick="goBack()"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  <h1>IR<span>MS</span> Pro. Team Dashboard</h1>
</header>

<div class="wrap">
  <aside class="sidebar">
    <div class="option" onclick="show('persons')"><i class="fas fa-user-check"></i> Active Persons</div>
    <div class="option" onclick="show('tasks')"><i class="fas fa-tasks"></i> Task Options</div>
  </aside>

  <main class="main">
    <!-- Active Persons -->
    <section id="persons" class="card view">
      <div class="content-header"><i class="fas fa-user-check"></i> Active Personnel</div>
      
      <div class="row">
        <span class="clickable" onclick="goToApproved('Staff')">Active Team Members</span>
        <span class="person-count">15</span>
      </div>
      
      <div class="row">
        <span class="clickable" onclick="goToApproved('Delivery Man')">Active Delivery Men</span>
        <span class="person-count">10</span>
      </div>
      
      <div class="row total">
        <span>Total Active Staff</span>
        <span id="totalStaff">0</span>
      </div>
    </section>

    <!-- Tasks -->
    <section id="tasks" class="card view" style="display:none; margin-top:16px;">
      <div class="content-header"><i class="fas fa-tasks"></i> Task Options</div>
      <div class="row task-item">Check stock levels weekly</div>
      <div class="row task-item">Coordinate with suppliers</div>
      <div class="row task-item">Process urgent requisitions</div>
    </section>
  </main>
</div>

<footer>
  &copy; 2025 Inventory Requisition & Management System
</footer>

<script>
  function show(id){
    document.querySelectorAll('.view').forEach(s=> s.style.display='none');
    document.getElementById(id).style.display='block';
    calculateTotals();
  }

  function goBack(){ window.history.back(); }

  function calculateTotals(){
    let staffTotal = 0;
    document.querySelectorAll('#persons .person-count').forEach(el=>{
      const n = parseInt(el.textContent) || 0;
      staffTotal += n;
    });
    document.getElementById('totalStaff').textContent = staffTotal;
  }

  function goToApproved(role){
    // Pass role to Approved List and include return page
    const url = `IRMS_Approved_List.php?role=${encodeURIComponent(role)}&return=IRMS_Pro_Team_dashboard.php`;
    window.location.href = url;
  }

  window.addEventListener('load', calculateTotals);
</script>
</body>
</html>
