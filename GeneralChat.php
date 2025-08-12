<?php
//checked 18/11/2024 //
session_start();
if(!isset($_SESSION['user_id']) && !isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id']))
{
    header("location: signin.php");
}


require_once('Connection.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $password=$_SESSION['password'];
    $query = "SELECT * FROM users WHERE user_id = '$user_id'AND password='$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

} elseif (isset($_SESSION['moderator_id'])) {
    $moderator_id = $_SESSION['moderator_id'];
    $password=$_SESSION['password'];
    $query = "SELECT * FROM moderator WHERE moderator_id = '$moderator_id'AND password='$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

} elseif (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $password=$_SESSION['password'];
    $query = "SELECT * FROM admin WHERE admin_id = '$admin_id'AND password='$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
}


$query  = "SELECT id, message, timestamp FROM chat_messages ORDER BY timestamp ASC";
$result= mysqli_query($conn,$query);




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Chat</title>
    <link href="Design.css" rel="stylesheet">
    <link href="GeneralChat.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2>Alumni Relationship & Networking System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image">
            </div>
            <?php if (isset($user_id)): ?>
                <ul>
                   <li><a href="UserDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                   <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                   <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                   <li><a href="Books.php"><i class="fas fa-book"></i>Books</a></li>
                   <li><a href="Upload.php"><i class="fas fa-upload"></i>Upload</a></li>
                   <li><a href="ContactUs.php"><i class="fas fa-address-book"></i>Contact</a></li>
               </ul> 
           <?php endif; ?>
           <?php if (isset($moderator_id)||isset($admin_id)): ?>

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
        <?php endif; ?>
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
            <div class="chat_room">
                <div class="messages" id="chat_messages">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='message'><strong>" . htmlspecialchars($row["id"]) . "</strong> " . htmlspecialchars($row["message"]) . " <span>" . $row["timestamp"] . "</span></div>";
                        }
                    } else {
                        echo "<div class='message'>No messages yet.</div>";
                    }
                    $conn->close();
                    ?>
                </div>
                <div class="message_input">
                    <form method="post" action="send_message.php">
                        <input type="text" name="message" id="chat-input" placeholder="Type your message..." required maxlength="150" style="width: 1100px; height: 40px;">
                        <button type="submit" style="height: 35px;">Send</button>
                    </form>
                </div>
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
        position: relative; top: 11px;
        bottom: 0;
        width: 100%;


    }
</style>
<footer>
    &copy; 2024 Alumni Networking System. All rights reserved.
</footer>
</body>
</html>
