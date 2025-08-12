<?php 
//checked 18/11/2024//
session_start();
if(!isset($_SESSION['user_id']))
{
    header("location: signin.php");
    exit();
}

require_once('Connection.php');
$user_id = $_SESSION['user_id'];
$password = $_SESSION['password'];
$query = "SELECT * FROM users WHERE user_id='$user_id' AND password='$password'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

$query2 = "SELECT * FROM statistics WHERE user_id='$user_id'";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_array($result2);

$query3 = "SELECT * FROM notices ORDER BY time_date_uploaded DESC";
$result3 = mysqli_query($conn, $query3);

$query4 = "SELECT * FROM warn WHERE warned_user_id='$user_id'";
$result4 = mysqli_query($conn, $query4);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <link href="Design.css" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2>Alumni Relationship & Networking System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="User Image">
            </div>
            <ul>
                <li><a href="UserDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                <li><a href="JoinEvents.php"><i class="fas fa-upload"></i>Events</a></li>
                <li><a href="main_job_page.php"><i class="fas fa-jobs"></i>Jobs</a></li>
                <li><a href="ContactUs.php"><i class="fas fa-address-book"></i>Contact</a></li>
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
                        <input type="search" placeholder="Search..." name="search">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <a href="logout.php" class="logout">Logout</a>
            </div>

            <div class="info_notice_container">
                <div class="information">

                    <div class="infocontainer">
                        <div class="personal_info">
                            <h4>Personal Info</h4>
                            <div class="info_item"><i class="fas fa-user"></i>Name: <?php echo $row['first_name']; echo "  "; echo $row['last_name'];?></div>
                            <div class="info_item"><i class="fas fa-id-badge"></i> User ID: <?php echo $row['user_id'];?></div>
                            <div class="info_item"><i class="fas fa-envelope"></i> Email: <?php echo $row['email'];?></div>
                            <div class="info_item"><i class="fas fa-calendar"></i> Date Of Birth <?php echo $row['date_of_birth'];?></div>
                            <div class="info_item"><i class="fas fa-phone"></i> Phone Number: <?php echo $row['phone_number'];?></div>
                        </div>
                        <div class="statistics">
                            <h4>Statistics</h4>
                            <div class="info_item"><i class="fas fa-upload"></i> Files Uploaded: <?php echo $row2['upload'];?></div>
                            <div class="info_item"><i class="fas fa-download"></i> Files Downloaded: <?php echo $row2['download'];?></div>
                            <div class="info_item"><i class="fas fa-chart-pie"></i> Ratio: <?php echo $row2['ratio'];?></div>
                        </div>
                    </div>
                </div>

                <div class="notice">
                    <h2>Notice</h2>
                    <marquee class="marq" direction="up" onmouseover="stop();" onmouseout="start();"> 
                        <?php  while ($rows4 = mysqli_fetch_array($result4)) { ?>
                            <h6><a style="color: red;"><?php echo $rows4['warned_reason']." "."Warned By"." ".$rows4['warned_by']." (".$rows4['time_date_uploaded'].")"?></a></h6>
                            <br>
                        <?php } ?>

                        <?php  while ($rows3 = mysqli_fetch_array($result3)) { ?>
                            <h6><a href="<?php echo $rows3['notice_link']?>"><?php echo $rows3['notice_text']." (".$rows3['time_date_uploaded'].")"?></a></h6>
                            <br>
                        <?php } ?>
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <style>
        footer {
            background-color: #4e73df;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: sticky;
            bottom: 0;
            width: 100%;
        }
    </style>
    
    <footer>
        &copy; 2025 Alumni Networking System. All rights reserved.
    </footer>
</body>
</html>
