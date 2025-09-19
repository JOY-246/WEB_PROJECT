<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Skills - IRMS Peon</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="IRMS_Peon_job.css" type="text/css">

</head>
<body>

<header>
  <a href="IRMS_CV_submit.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
  Select Skills - Peon
</header>

<div class="main">
  <form id="peonSkillsForm" onsubmit="return validateForm()">
    <div class="cards">
      <div class="card" data-skill="Delivery"><i class="fas fa-truck"></i>Delivery</div>
      <div class="card" data-skill="Carrying Items"><i class="fas fa-box"></i>Carrying Items</div>
      <div class="card" data-skill="Stock Arrangement"><i class="fas fa-warehouse"></i>Stock Arrangement</div>
      <div class="card" data-skill="Cleaning"><i class="fas fa-broom"></i>Cleaning</div>
    </div>

    <div class="experience-container">
      <label for="experience">Experience (Years):</label>
      <input type="number" id="experience" name="experience" min="0">
    </div>

    <div class="error" id="errorSkills">Please select at least 2 skills.</div>
    <div class="error" id="errorExp">Please enter a valid experience.</div>

    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Skills</button>
  </form>
</div>

<div class="footer"></div>

<script>
const cards = document.querySelectorAll('.card');
let selectedSkills = [];

// Toggle skill selection
cards.forEach(card => {
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

// Form validation
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
    alert("Selected Skills: " + selectedSkills.join(', ') + "\nExperience: " + exp + " years");
    // Redirect to Peon Dashboard page
    window.location.href = "IRMS_Peon_dashboard.php";
    return false;
  }

  return false;
}
</script>

</body>
</html>
