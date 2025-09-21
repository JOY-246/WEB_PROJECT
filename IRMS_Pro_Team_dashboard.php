<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IRMS Pro. Team Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="IRMS_Pro_Team_dashboard.css" type="text/css"/>
</head>
<body>

<header class="header">
  <div class="nav-buttons">
    <a href="#" id="backBtn" onclick="goBack()"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  <h1>IR<span>MS</span> Pro. Team Dashboard</h1>
</header>

<div class="wrap">
  <aside class="sidebar">
    <div class="option" onclick="show('persons')"><i class="fas fa-user-check"></i> Active Persons</div>
    <div class="option" onclick="show('products')"><i class="fas fa-boxes"></i> Product Management</div>
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

    <!-- Product Management -->
    <section id="products" class="card view" style="display:none; margin-top:16px;">
      <div class="content-header"><i class="fas fa-boxes"></i> Product Management</div>
      <div id="productList">
        <div class="row product-item"><div class="product-name"><img src="https://img.icons8.com/ios-filled/50/chair.png" alt="Chair">Chair</div><span class="qty">20</span></div>
        <div class="row product-item"><div class="product-name"><img src="https://img.icons8.com/ios-filled/50/table.png" alt="Table">Table</div><span class="qty">15</span></div>
        <div class="row total total-row"><span>Total Products</span><span id="totalProducts">0</span></div>
      </div>
      <button class="more-btn" id="moreBtn" onclick="addProduct()"><i class="fas fa-plus"></i> Add product</button>
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

  function goBack(){ window.location.href = 'previouspage.html'; }

  function calculateTotals(){
    let staffTotal = 0;
    document.querySelectorAll('#persons .person-count').forEach(el=>{
      const n = parseInt(el.textContent) || 0;
      staffTotal += n;
    });
    document.getElementById('totalStaff').textContent = staffTotal;

    let productTotal = 0;
    document.querySelectorAll('#productList .product-item .qty').forEach(el=>{
      const n = parseInt(el.textContent) || 0;
      productTotal += n;
    });
    document.getElementById('totalProducts').textContent = productTotal;
  }

  function addProduct(){
    const name = prompt('Enter product name (e.g. Marker):');
    if(!name) return;
    const qText = prompt('Enter quantity for "'+name+'":');
    const qty = parseInt(qText) || 0;
    if(qty <= 0){ alert('Quantity must be positive.'); return; }

    const productList = document.getElementById('productList');
    const totalRow = productList.querySelector('.total-row');
    const newRow = document.createElement('div');
    newRow.className = 'row product-item';
    newRow.innerHTML = `<div class="product-name"><img src="https://img.icons8.com/ios-filled/50/plus.png" alt="item"> ${escapeHtml(name)}</div><span class="qty">${qty}</span>`;
    productList.insertBefore(newRow, totalRow);
    calculateTotals();
  }

  function escapeHtml(text){
    return text.replace(/[&<>"'`=\/]/g, s=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s]));
  }

  function goToApproved(role){
    window.location.href = `IRMS_Approved_List.php?role=${encodeURIComponent(role)}`;
  }

  window.addEventListener('load', calculateTotals);
</script>
</body>
</html>
