<?php
// Applicant data for dynamic counts
$applicants = [
  ['name'=>"John Doe",'email'=>"john@example.com",'role'=>"Staff",'status'=>"Pending",'skills'=>"Excel, Reporting",'experience'=>"3 years"],
  ['name'=>"Mary Smith",'email'=>"mary@example.com",'role'=>"Delivery Man",'status'=>"Approved",'skills'=>"", 'experience'=>"5 years"],
  ['name'=>"Ali Khan",'email'=>"ali@example.com",'role'=>"Staff",'status'=>"Approved",'skills'=>"Inventory, Logistics",'experience'=>"2 years"],
  ['name'=>"Sophia Lee",'email'=>"sophia@example.com",'role'=>"Delivery Man",'status'=>"Approved",'skills'=>"", 'experience'=>"1 year"]
];

// Count approved roles
$teamMembers = count(array_filter($applicants, fn($a) => $a['status']==="Approved" && $a['role']==="Staff"));
$deliveryMen = count(array_filter($applicants, fn($a) => $a['status']==="Approved" && $a['role']==="Delivery Man"));
$totalActive = $teamMembers + $deliveryMen;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IRMS - Manager Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="IRMS_Manager_dashboard.css">
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="nav-buttons">
    <a href="IRMS_Access_system.php" class="nav-btn"><i class="fas fa-arrow-left"></i> Back</a>
    <a href="#" class="nav-btn">Next <i class="fas fa-arrow-right"></i></a>
  </div>
  <h1>IR<span>MS</span> Manager Dashboard</h1>
</div>

<!-- Main Content -->
<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-option active">
      <a href="IRMS_Applicants.php"><i class="fas fa-users"></i> Applicants</a>
    </div>
    <div class="sidebar-option" data-target="active-persons">
      <i class="fas fa-user-check"></i> Active Persons
    </div>
    <div class="sidebar-option" data-target="salary">
      <i class="fas fa-money-bill-wave"></i> Salary
    </div>
    <div class="sidebar-option" data-target="product-management">
      <i class="fas fa-boxes"></i> Product Management
    </div>
  </div>

  <!-- Content Area -->
  <div class="main-content">
    <div class="content-header">
      <i class="fas fa-money-bill-wave"></i> Salary Information
    </div>
    
    <div class="salary-details">
      <div class="salary-item">
        <span>Applicants Processing Cost</span>
        <span class="salary-value">$4,500.00</span>
      </div>
      <div class="salary-item">
        <span>Active Personnel Salaries</span>
        <span class="salary-value">$28,750.00</span>
      </div>
      <div class="salary-item">
        <span>Management Team Compensation</span>
        <span class="salary-value">$15,300.00</span>
      </div>
      <div class="salary-item total">
        <span>Total Monthly Salary Expenditure</span>
        <span class="salary-value">$48,550.00</span>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Inventory Requisition & Management System</p>
</div>

<script>
  // Sidebar SPA handling
  document.querySelectorAll('.sidebar-option').forEach(option => {
    option.addEventListener('click', function() {
      const link = this.querySelector('a');
      if (link) return; // normal link (Applicants)
      document.querySelectorAll('.sidebar-option').forEach(opt => opt.classList.remove('active'));
      this.classList.add('active');
      updateContent(this.getAttribute('data-target'));
    });
  });

  function updateContent(target) {
    const contentHeader = document.querySelector('.content-header');
    const contentBody = document.querySelector('.salary-details');

    switch(target) {
      case 'active-persons':
        contentHeader.innerHTML = '<i class="fas fa-user-check"></i> Active Personnel';
        contentBody.innerHTML = `
          <div class="salary-item">
            <span><i class="fas fa-user-friends"></i> 
              <a href="IRMS_Approved_List.php?role=Staff" class="inline-link">Active Team Members</a>
            </span>
            <span class="salary-value"><?php echo $teamMembers; ?></span>
          </div>
          <div class="salary-item">
            <span><i class="fas fa-truck"></i> 
              <a href="IRMS_Approved_List.php?role=Delivery Man" class="inline-link">Active Delivery Men</a>
            </span>
            <span class="salary-value"><?php echo $deliveryMen; ?></span>
          </div>
          <div class="salary-item total">
            <span>Total Active Staff</span>
            <span class="salary-value"><?php echo $totalActive; ?></span>
          </div>
        `;
        break;

      case 'salary':
        contentHeader.innerHTML = '<i class="fas fa-money-bill-wave"></i> Salary Information';
        contentBody.innerHTML = `
          <div class="salary-item"><span>Applicants Processing Cost</span><span class="salary-value">$4,500.00</span></div>
          <div class="salary-item"><span>Active Personnel Salaries</span><span class="salary-value">$28,750.00</span></div>
          <div class="salary-item"><span>Management Team Compensation</span><span class="salary-value">$15,300.00</span></div>
          <div class="salary-item total"><span>Total Monthly Salary Expenditure</span><span class="salary-value">$48,550.00</span></div>
        `;
        break;

      case 'product-management':
        contentHeader.innerHTML = '<i class="fas fa-boxes"></i> Product Management';
        const products = [
          { name: "Chair", icon: "fas fa-chair", qty: 25 },
          { name: "Table", icon: "fas fa-table", qty: 15 },
          { name: "Computer", icon: "fas fa-desktop", qty: 40 }
        ];
        renderProducts(products);
        break;
    }
  }

  // Render Products with Add More
  function renderProducts(products) {
    const contentBody = document.querySelector('.salary-details');
    contentBody.innerHTML = "";

    let total = 0;
    products.forEach(p => {
      total += p.qty;
      contentBody.innerHTML += `
        <div class="salary-item">
          <span><i class="${p.icon}"></i> ${p.name}</span>
          <span class="salary-value">${p.qty}</span>
        </div>
      `;
    });

    contentBody.innerHTML += `
      <div class="salary-item total">
        <span>Total Products</span>
        <span class="salary-value">${total}</span>
      </div>
    `;

    const addBtn = document.createElement("button");
    addBtn.textContent = "Add More";
    addBtn.className = "add-btn";
    addBtn.onclick = () => {
      const name = prompt("Enter product name:");
      const qty = parseInt(prompt("Enter quantity:"), 10);
      if (name && !isNaN(qty) && qty > 0) {
        products.push({ name, icon: "fas fa-box", qty });
        renderProducts(products);
      }
    };
    contentBody.appendChild(addBtn);
  }
</script>

</body>
</html>
