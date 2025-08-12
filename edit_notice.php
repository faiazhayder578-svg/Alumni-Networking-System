<?php
//checked
session_start();
require_once('Connection.php');

if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: signin.php");
    exit();
}


$notice_id = $_GET['notice_id']; 
$query = "SELECT * FROM notices WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $notice_id);
$stmt->execute();
$result = $stmt->get_result();
$notice = $result->fetch_assoc();

if (!$notice) {
    echo "Notice not found.";
    exit();
}

$notice_text = $notice['notice_text'];
$notice_link = $notice['notice_link'];
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notice</title>
    <link href="Design.css" rel="stylesheet">
    <link href="Notice.css" rel="stylesheet">
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
                <img src="images/Blank_Image.png" alt="image";>
            </div>
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['moderator_id'])): ?>
                    <li><a href="ModeratorOperation.php"><i class="fas fa-user-tie"></i>Moderator Operations</a></li>
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
            <!-- Header -->
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


            <div class="edit_notice_container">
                <h3>Edit Notice</h3>
                <form method="POST" action="edit_notice_submission.php">
                    <input type="hidden" name="notice_id" value="<?php echo $notice_id; ?>">
                    <textarea name="notice_text" placeholder="Edit notice text"  required rows="3" class="form_control" style="text-align: left; resize: none;"><?php echo htmlspecialchars($notice_text); ?></textarea>


                    <input type="url" name="notice_link" placeholder="Edit notice link (optional)" class="form_control" 
                    value="<?php echo htmlspecialchars($notice_link); ?>">
                    <button type="submit" name="edit_notice" class="btn btn-primary">Update Notice</button>
                </form>
                




            </div>
        </div>
    </div>
</body>
</html>
