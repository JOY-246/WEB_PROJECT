<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>IRMS Applicants</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="IRMS_Applicants.css"/>
</head>
<body>

<header class="header"> 
  <div class="nav-buttons"> 
    <a href="previouspage.html"><i class="fas fa-arrow-left"></i> Back</a> 
    <a href="nextpage.html">Next <i class="fas fa-arrow-right"></i></a> 
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="applicantList"></tbody>
      </table>
    </section>

    <!-- Application Status -->
    <section id="status" class="card view" style="display:none;">
      <div class="content-header"><i class="fas fa-clipboard-check"></i> Application Status</div>
      <div class="status-counts">
        <div class="pending clickable" onclick="showList('Pending')">
          Pending List<br><span id="pendingCount">0</span>
        </div>
        <div class="approved clickable" onclick="showList('Approved')">
          Approved List<br><span id="approvedCount">0</span>
        </div>
      </div>

      <div id="statusList" style="margin-top:20px;">
        <!-- Dynamic Table will be injected here -->
      </div>
    </section>
  </main>
</div>

<footer>&copy; 2025 Inventory Requisition & Management System</footer>

<script>
// Initial applicants
let applicants = [
  {name:"John Doe", email:"john@example.com", role:"Staff", status:"Pending", skills:"Excel, Reporting", experience:"3 years"},
  {name:"Mary Smith", email:"mary@example.com", role:"Delivery Man", status:"Approved", skills:"", experience:"5 years"},
  {name:"Ali Khan", email:"ali@example.com", role:"Staff", status:"Rejected", skills:"Inventory, Logistics", experience:"2 years"},
  {name:"Sophia Lee", email:"sophia@example.com", role:"Delivery Man", status:"Pending", skills:"", experience:"1 year"}
];

// SPA Render functions
function renderApplicants(){
  const container = document.getElementById('applicantList');
  container.innerHTML = "";
  applicants.forEach((a,index)=>{
    const skillsText = a.role === "Delivery Man" ? "N/A" : a.skills;
    const approveDisabled = a.status !== "Pending" ? "disabled" : "";
    const rejectDisabled = a.status !== "Pending" ? "disabled" : "";

    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${a.name}</td>
      <td>${a.email}</td>
      <td>${a.role}</td>
      <td>${skillsText}</td>
      <td>${a.experience}</td>
      <td class="status-btns">
        <button class="approve-btn" onclick="updateStatus(${index}, 'Approved')" ${approveDisabled}
          style="${a.status==='Approved'?'background:#218838;':''}">Approve</button>
        <button class="reject-btn" onclick="updateStatus(${index}, 'Rejected')" ${rejectDisabled}
          style="${a.status==='Rejected'?'background:#bd2130;':''}">Reject</button>
      </td>
    `;
    container.appendChild(row);
  });
  updateCounts();
}

function updateStatus(index, status){
  applicants[index].status = status;
  renderApplicants();
  updateCounts();
  if(currentList) showList(currentList);
}

function updateCounts(){
  document.getElementById('pendingCount').innerText = applicants.filter(a=>a.status==="Pending").length;
  document.getElementById('approvedCount').innerText = applicants.filter(a=>a.status==="Approved").length;
}

let currentList = null; // track which list is being displayed

function showList(status){
  currentList = status;
  const container = document.getElementById('statusList');
  container.innerHTML = "";

  const filtered = applicants.filter(a => a.status === status);
  if(filtered.length === 0){
    container.innerHTML = "<p>No applicants.</p>";
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
    <tbody>
    </tbody>
  `;

  const tbody = table.querySelector('tbody');
  filtered.forEach(a => {
    const skillsText = a.role === "Delivery Man" ? "N/A" : a.skills;
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${a.name}</td>
      <td>${a.email}</td>
      <td>${a.role}</td>
      <td>${skillsText}</td>
      <td>${a.experience}</td>
    `;
    tbody.appendChild(row);
  });

  container.appendChild(table);
}

function show(id){
  document.querySelectorAll('.view').forEach(s=> s.style.display='none');
  document.getElementById(id).style.display='block';
}

renderApplicants();
updateCounts();
</script>
</body>
</html>
