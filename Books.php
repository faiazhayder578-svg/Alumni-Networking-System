<?php 
//checked 18/11/2024 //
session_start();
if(!isset($_SESSION['user_id']))
{
  header("location: signin.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="icon.png" type="image/x-icon">
  <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <title>Books</title>
</head>
<body>
  <header>
    <nav>
      <div class="logo"><a href="#">Library Management System</a></div>
      <ul class="nav-links">
        <li><a href="UserDashboard.php">Dashboard</a></li>
        <li><a href="Upload.php">Upload</a></li>
        <li><a href="ContactUs.php">Contact</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <div class="container">
    <div class="leftMenu">
      <input type="text" placeholder="Search..." class="search">
      <h1>Categories :</h1>
      <div class="cats"></div>
    </div>
    <div class="content">
      <div class="products"></div>
    </div>
  </div>
  <script src="Books.js"></script> <!-- Ensure this path is correct -->
  <footer>
    <p>&copy; 2024 Library Management System. All rights reserved.</p>
  </footer>
</body>
</html>
