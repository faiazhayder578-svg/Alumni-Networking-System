<?php 

session_start();
if(!isset($_SESSION['admin_id']))
{
    header("location: signin.php");
}


require_once('Connection.php');




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Operation</title>
    <link href="Design.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Poppins">
    
</head>
<style>



    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    
    .dashboard-block {
        background-color: #f5f5f5; 
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        text-decoration: none;
        color: #333;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .dashboard-block:hover {
        transform: translateY(-5px); 
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .dashboard-block i {
        color: #4e73df;
        margin-bottom: 15px;
    }

    .dashboard-block h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }
</style>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2 style="text-transform: uppercase; text-align: center;">Alumni Relationship & Networking System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image" style="width:100%; margin-bottom: 20px;">
            </div>
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</li>
                    <li><a href="GeneralChat.php"><i class="fas fa-message"></i> General Chat</a></li>

                </ul>
                <div class="social_media">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="main_content">
                <div class="header">
                    <div class="search_container">
                        <form method="post" action="">
                            <input type="search" placeholder="Search..." name="">
                            <button type="submit">Search</button>
                        </form>
                    </div>

                    <div class="logout_container">
                        <a href="logout.php"><span class="logout">Logout</span></a>
                    </div>
                </div> 
                <div class="inner_container">

                    <h3>Admin Operations</h3>
                    <div class="dashboard-grid">

                        <a href="checkUploads.php" class="dashboard-block">
                            <i class="fas fa-upload fa-3x"></i>
                            <h3>Check Uploads</h3>
                        </a>

                        <a href="ModeratorSignUp.php" class="dashboard-block">
                            <i class="fas fa-user-plus fa-3x"></i>
                            <h3>Create Moderator Account</h3>
                        </a>

                        <a href="UserList.php" class="dashboard-block">
                            <i class="fas fa-users fa-3x"></i>
                            <h3>User List</h3>
                        </a>

                        <a href="ModeratorList.php" class="dashboard-block">
                            <i class="fas fa-user-shield fa-3x"></i>
                            <h3>Moderator List</h3>
                        </a>
                        <a href="CreateNotice.php" class="dashboard-block">
                            <i class="fas fa-clipboard fa-3x"></i>
                            <h3>Create Notice</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
            footer{
                background-color: #4e73df;
                color: #ffffff;
                text-align: center;
                padding: 10px 0;
                position: sticky;
                bottom: 0;
                width: 100%;}
            </style>
            <footer>
                &copy; 2025 Alumni Networking System. All rights reserved.
            </footer>

        </body>
        </html>
