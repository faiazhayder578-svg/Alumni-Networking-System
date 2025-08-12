<?php 
// checked 18/11/2024 //
session_start();
if(!isset($_SESSION['user_id']) && !isset($_SESSION['moderator_id']) &&!isset($_SESSION['admin_id']))
{
  header("location: signin.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="styles.css" />
  <title>Product Detail</title>
</head>
<body>
  <header>
    <nav>
      <div class="logo"><a href="#">Library Management System</a></div>
      <ul class="nav-links">
        <?php if(isset($_SESSION['user_id'])) { ?>
          <li><a href="UserDashboard.php">Dashboard</a></li>
          <li><a href="Upload.php">Upload</a></li>
          <li><a href="ContactUs.php">Contact</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php } ?>
        <?php if(isset($_SESSION['admin_id'])|| isset($_SESSION['moderator_id'])) { ?>
          <li><a href="Moderator&AdminDashboard.php">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php } ?>

      </ul>
    </nav>
  </header>

  <div class="product-detail-container">
    <div class="product-detail">
      <!-- Product details will be populated by JavaScript -->
    </div>
  </div>

  <script src="productDetail.js"></script>

  <footer>
    <p>&copy; 2024 Library Management System. All rights reserved.</p>
  </footer>




</body>
</html>

