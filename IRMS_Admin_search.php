<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - View Users</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Admin_search.css" type="text/css">
</head>
<body>

<header>
  Admin - View Users
</header>

<div class="main">

  <!-- Buttons container -->
  <div class="buttons-container">
    <button class="back-btn" onclick="window.location.href='IRMS_Admin_login.php';"><i class="fas fa-sign-out-alt"></i> Logout</button>
    <button class="product-btn" onclick="window.location.href='IRMS_Admin_Product_dashboard.php';"><i class="fas fa-box"></i> Product Page</button>
  </div>

  <!-- Search -->
  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search by Name or Serial Number">
    <button onclick="searchUser()"><i class="fas fa-search"></i> Search</button>
  </div>

  <!-- Users Table -->
  <table id="usersTable">
    <thead>
      <tr>
        <th>Serial Number</th>
        <th>Name</th>
        <th>Position</th>
        <th>Skills</th>
        <th>Experience</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data will be populated here via JavaScript or server-side -->
    </tbody>
  </table>

  <div class="no-data" id="noData" style="display:none;">No users found.</div>

</div>

<script>
// Sample data fetched from database (replace with actual server-side fetch)
const usersData = [
  {serial: '001', name: 'John Doe', position: 'Staff', skills: 'Inventory, Reporting', exp: 3},
  {serial: '002', name: 'Jane Smith', position: 'Peon', skills: 'Delivery, Cleaning', exp: 2},
  {serial: '003', name: 'Mike Johnson', position: 'Intern', skills: 'Data Entry', exp: 1},
  {serial: '004', name: 'Sara Ali', position: 'Staff', skills: 'Customer Support, Time Management', exp: 4},
];

const tableBody = document.querySelector('#usersTable tbody');

function displayUsers(data){
  tableBody.innerHTML = "";
  if(data.length === 0){
    document.getElementById('noData').style.display = 'block';
    return;
  } else {
    document.getElementById('noData').style.display = 'none';
  }

  data.forEach(user => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${user.serial}</td>
      <td>${user.name}</td>
      <td>${user.position}</td>
      <td>${user.skills}</td>
      <td>${user.exp} years</td>
    `;
    tableBody.appendChild(row);
  });
}

// Initial display
displayUsers(usersData);

// Search function
function searchUser(){
  const query = document.getElementById('searchInput').value.toLowerCase();
  const filtered = usersData.filter(user =>
    user.name.toLowerCase().includes(query) || user.serial.includes(query)
  );
  displayUsers(filtered);
}
</script>

</body>
</html>