<?php 
//checked 18/11/2024//
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("location: signin.php");
}

require_once('Connection.php');

$query = "select * from moderator order by first_name asc";
$result= mysqli_query($conn,$query);
$query2 = "select count(*) as total_moderator from moderator ";
$result2= mysqli_query($conn,$query2);
$row2=mysqli_fetch_array($result2);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator List</title>
    <link href="Design.css" rel="stylesheet">
    <link href="List.css" rel="stylesheet">
    <link href="popup.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Poppins">

</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2 style="color: #fff;text-transform:uppercase;text-align:center;font-size:2.1rem; position: relative;bottom: 20px;">Library Management System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image" style="position: relative; bottom: 30px;">
            </div>
            
            <ul style="position: relative; bottom: 30px;">
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</li>
                    <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                    
                </ul> 
                <div class="social_media" style="position: relative; bottom: 40px;">
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

                    <div class="headline"><h2>Moderators</h2></div>
                    
                    <div class="container">
                        <div align="right"><form method="post" action="#">
                            <input type="search" placeholder="Search..." name="search_id">
                            <button type="submit">Search</button>
                        </form></div>
                        <table class="table table-striped table-hover">
                            <thead>

                               <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Moderator ID</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php while ($rows = mysqli_fetch_array($result)) { 

                                $check_ban_query = "SELECT * FROM ban WHERE banned_user_id = '" . $rows['moderator_id'] . "'";
                                $check_ban_result = mysqli_query($conn, $check_ban_query);
                                $is_banned = mysqli_num_rows($check_ban_result) > 0;
                                ?>
                                <tr>
                                    <td><?php echo $rows['first_name']; ?></td>
                                    <td><?php echo $rows['last_name']; ?></td>
                                    <td><?php echo $rows['moderator_id']; ?></td>
                                    <td><?php echo $rows['phone_number']; ?></td>
                                    <td><?php echo $rows['email']; ?></td>
                                    <td><?php echo $rows['password']; ?></td>
                                    <td>
                                        <div style="display: flex; justify-content: center; gap: 10px;">
                                            <button 
                                            class="btn btn-danger warn-button" 
                                            data-user-id="<?php echo $rows['moderator_id']; ?>" 
                                            data-user-name="<?php echo $rows['first_name'] . " " . $rows['last_name']; ?>">
                                            Warn
                                        </button>
                                        <?php if ($is_banned) { ?>
                                            <a href="unban.php?id=<?php echo $rows['moderator_id']; ?>&list=moderator" class="btn btn-warning">Unban</a>
                                        <?php } else { ?>
                                            <a href="ban.php?id=<?php echo $rows['moderator_id']; ?>&list=moderator" class="btn btn-danger">Ban</a>
                                        <?php } ?>
                                        <a href="delete_moderator.php?id=<?php echo $rows['moderator_id']; ?>" class="btn btn-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div>Total Moderators: <?php  echo $row2['total_moderator'] ?></div>
                <ul class="pagination">
                    <li><a href="#">&laquo;</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">&raquo;</a></li>
                </ul>
            </div>

        </div> 
    </div>

</div>

<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <h3>Warn Moderator</h3>
        <form id="warnForm" method="post" action="warn.php">
            <input type="hidden" name="user_id" id="user_id">
            <div>
                <label for="message">Message:</label>
                <textarea id="message" name="message" required placeholder="Enter your warning message"></textarea>
                <input type="hidden" name="hidden_value" value="moderator">
            </div>
            <button type="submit" class="btn btn-primary">Send Warning</button>
        </form>
    </div>
</div>

<script src="popup.js"></script>
</body>
</html>
