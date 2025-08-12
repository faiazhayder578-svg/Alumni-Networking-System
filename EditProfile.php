<?php 
//checked 18/11/2024//
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
    exit();
}

require_once('Connection.php');

if (isset($_SESSION['user_id'])) {
    $table = 'users';
    $id_column = 'user_id';
    $id = $_SESSION['user_id'];
} elseif (isset($_SESSION['moderator_id'])) {
    $table = 'moderator';
    $id_column = 'moderator_id';
    $id = $_SESSION['moderator_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $table = 'admin';
    $id_column = 'admin_id';
    $id = $_SESSION['admin_id'];
} else {
    die("Invalid session!");
}


$query = "SELECT * FROM $table WHERE $id_column='$id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found!");
}
$current_data = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="Design.css" rel="stylesheet">
    <link href="EditProfile.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <aside class="side_container">
            <h2>Alumni Relationship & Networking System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="Profile Image">
            </div>
            <?php if (isset($_SESSION['moderator_id']) || isset($_SESSION['admin_id'])) { ?>
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
            <?php } ?>

            <?php if (isset($_SESSION['user_id'])) { ?>
                <ul>
                <li><a href="UserDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                <li><a href="JoinEvents.php"><i class="fas fa-upload"></i>Events</a></li>
                <li><a href="main_job_page.php"><i class="fas fa-jobs"></i>Jobs</a></li>
                <li><a href="ContactUs.php"><i class="fas fa-address-book"></i>Contact</a></li>
                </ul>
            <?php } ?>
            <div class="social_media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </aside>
        <main class="main_content">
            <header class="header">
                <div class="search_container">
                    <form method="post" action="#">
                        <input type="search" placeholder="Search..." name="search">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="logout_container">
                    <a href="logout.php"><span class="logout">Logout</span></a>
                </div>
            </header>
            <section class="inner_container">
                <div class="container">
                    <h2>Edit Profile Information</h2>
                    <form id="edit_profile" method="post" action="UpdateProfile.php">
                        <div class="row">
                            <div class="form-group">
                                <input type="text" name="first_name" id="details" placeholder=" " maxlength="20" 
                                value="<?= htmlspecialchars($current_data['first_name']) ?>" required>
                                <label for="fname">First Name<span style="color: red;">*</span></label>
                            </div>
                            <div class="form-group">
                                <input type="text" name="last_name" id="details" placeholder=" " maxlength="20" 
                                value="<?= htmlspecialchars($current_data['last_name']) ?>">
                                <label for="lname">Last Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <input type="email" name="email" id="details" placeholder=" " maxlength="50" 
                                value="<?= htmlspecialchars($current_data['email']) ?>" required>
                                <label for="email">Email Address<span style="color: red;">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <input type="password" name="current_password" id="details" placeholder=" " maxlength="16" required>
                                <label for="current_password">Current Password<span style="color: red;">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" style="font-size: 0.9em;">
                                <input type="password" name="new_password" id="details" placeholder=" " maxlength="16">
                                <label for="new_password">New Password</label>
                            </div>
                            <div class="form-group" style="font-size: 0.9em;">
                                <input type="password" name="re_password" id="details" placeholder=" " maxlength="16">
                                <label for="re_password">Re-enter New Password</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group">
                                <input type="date" name="dob" id="details" placeholder=" " 
                                value="<?= htmlspecialchars($current_data['date_of_birth']) ?>" required>
                                <label for="dob">Date of Birth<span style="color: red;">*</span></label>
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone_number" id="details" placeholder=" " maxlength="14" 
                                value="<?= htmlspecialchars($current_data['phone_number']) ?>">
                                <label for="phone_number">Phone Number</label>
                            </div>
                        </div>
                        <label>Gender:</label><br>
                        <input type="radio" name="gender" value="M" <?= $current_data['gender'] === 'M' ? 'checked' : '' ?>> Male
                        <input type="radio" name="gender" value="F" <?= $current_data['gender'] === 'F' ? 'checked' : '' ?>> Female
                        <br><br>
                        <input type="submit" value="Update Profile" name="submit" id="submit">
                    </form>
                </div>
            </section>
        </main>
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
