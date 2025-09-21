<?php
$roleFilter = isset($_GET['role']) ? $_GET['role'] : null;
$applicants = [
  ['name'=>"John Doe",'email'=>"john@example.com",'role'=>"Staff",'status'=>"Pending",'skills'=>"Excel, Reporting",'experience'=>"3 years"],
  ['name'=>"Mary Smith",'email'=>"mary@example.com",'role'=>"Delivery Man",'status'=>"Approved",'skills'=>"", 'experience'=>"5 years"],
  ['name'=>"Ali Khan",'email'=>"ali@example.com",'role'=>"Staff",'status'=>"Approved",'skills'=>"Inventory, Logistics",'experience'=>"2 years"],
  ['name'=>"Sophia Lee",'email'=>"sophia@example.com",'role'=>"Delivery Man",'status'=>"Approved",'skills'=>"", 'experience'=>"1 year"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Approved List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="IRMS_Approved_List.css"/>
</head>
<body>
<header class="header">
  <h1>IR<span class="yellow">MS</span> Approved List</h1>
  <div class="nav-buttons">
    <a href="IRMS_Applicants.php"><i class="fas fa-arrow-left"></i> Back to All Applicants</a>
  </div>
</header>

<main class="main">
  <section class="card">
    <table>
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
        <?php
        foreach($applicants as $a){
          if($a['status']==='Approved' && (!$roleFilter || $a['role']===$roleFilter)){
            $skillsText = $a['role']==='Delivery Man' ? 'N/A' : $a['skills'];
            echo "<tr>
                    <td>{$a['name']}</td>
                    <td>{$a['email']}</td>
                    <td>{$a['role']}</td>
                    <td>{$skillsText}</td>
                    <td>{$a['experience']}</td>
                  </tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </section>
</main>

<footer>&copy; 2025 Inventory Requisition & Management System</footer>
</body>
</html>
