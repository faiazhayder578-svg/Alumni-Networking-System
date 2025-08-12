<?php 
//checked 18/11/2024//
session_start();
if(!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
}

require_once('Connection.php');

if (isset($_SESSION['moderator_id'])) {
    $moderator_id = $_SESSION['moderator_id'];
    $id = $_SESSION['moderator_id'];
    $password = $_SESSION['password'];
    $query = "SELECT * FROM moderator WHERE moderator_id = '$moderator_id' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

} elseif (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $id = $_SESSION['admin_id'];
    $password = $_SESSION['password'];
    $query = "SELECT * FROM admin WHERE admin_id = '$admin_id' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
}

$query2= "SELECT * FROM notices ORDER BY time_date_uploaded DESC";
$result2 = mysqli_query($conn, $query2);

$query3 = "SELECT * FROM warn WHERE warned_user_id='$id'";
$result3 = mysqli_query($conn, $query3);

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
                                <?php 
                // Check if user has a profile picture
                if (!empty($row['profile_picture']) && file_exists("uploads/profile_pictures/" . $row['profile_picture'])) {
                    $profile_src = "uploads/profile_pictures/" . $row['profile_picture'];
                } else {
                    // Use default image if no profile picture or file doesn't exist
                    $profile_src = "images/Blank_Image.png";
                }
                ?>
                <img src="<?php echo $profile_src; ?>" alt="User Profile Picture" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                     onerror="this.src='images/Blank_Image.png';">
            </div>
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <?php if (isset($admin_id)): ?>
                 <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</li>
                 <?php endif; ?>
                 <?php if (isset($moderator_id)): ?>
                     <li><a href="ModeratorOperation.php"><i class="fas fa-user-tie"></i>Moderator <br> &nbsp;&nbsp;&nbsp;&nbsp; Operations</li>
                     <?php endif; ?>
                     <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>

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
                                <div class="info_item"><i class="fas fa-user"></i>Name: <?php echo $row['first_name'] . " " . $row['last_name'];?></div>
                                <div class="info_item"><i class="fas fa-id-badge"></i> ID: <?php echo isset($moderator_id) ? $row['moderator_id'] : $row['admin_id']; ?></div>
                                <div class="info_item"><i class="fas fa-envelope"></i> Email: <?php echo $row['email']; ?></div>
                                <div class="info_item"><i class="fas fa-calendar"></i> Date Of Birth: <?php echo $row['date_of_birth'];?></div>
                                <div class="info_item"><i class="fas fa-phone"></i> Phone Number: <?php echo $row['phone_number'];?></div>
                            </div>
                            <div class="statistics">
                                <h4>Statistics</h4>
                                <div class="info_item"><i class="fas fa-upload"></i> Files Uploaded: </div>
                                <div class="info_item"><i class="fas fa-download"></i> Files Downloaded: </div>
                                <div class="info_item"><i class="fas fa-chart-pie"></i> Ratio: </div>
                            </div>
                        </div>
                    </div>

                    <div class="notice">
                        <h2>Notice</h2>

                        <marquee class="marq" direction="up" onmouseover="stop();" onmouseout="start();"> 
                            <?php  while ($rows3 = mysqli_fetch_array($result3)) { ?>
                                <h6><a style="color: red;"><?php echo $rows3['warned_reason']." "."Warned By"." ".$rows3['warned_by']." (".$rows3['time_date_uploaded'].")"?></a></h6>
                                <br>
                            <?php } ?>

                            <?php  while ($rows2 = mysqli_fetch_array($result2)) { ?>
                                <h6><a href="<?php echo $rows2['notice_link']?>"><?php echo $rows2['notice_text']." "."(".$rows2['time_date_uploaded'].")"?></a></h6>
                                <br>
                            <?php } ?>
                        </marquee>
                        
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
