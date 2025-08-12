<?php
//checked 18/11/2024 //

session_start();
if(!isset($_SESSION['user_id']))
{
    header("location: signin.php");
    exit();
}

require_once('Connection.php');
$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';
// Get user profile data for sidebar image
$user_query = "SELECT profile_picture FROM users WHERE user_id='$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="Design.css" rel="stylesheet">
    <link href="ContactUs.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2>Alumni Relationship & Networking System</h2>
             <div class="image">
                <?php 
                // Check if user has a profile picture
                if (!empty($user_data['profile_picture']) && file_exists("uploads/profile_pictures/" . $user_data['profile_picture'])) {
                    $profile_src = "uploads/profile_pictures/" . $user_data['profile_picture'];
                } else {
                    // Use default image if no profile picture or file doesn't exist
                    $profile_src = "images/Blank_Image.png";
                }
                ?>
                <img src="<?php echo $profile_src; ?>" alt="Profile Picture" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                     onerror="this.src='images/Blank_Image.png';">
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

            <div class="contact_location_container">
                <div class="contact">
                    <h3 >Contact Us</h3>

                    <form id="contact_us" action="https://api.web3forms.com/submit" method="POST">
                       <input type="hidden" name="access_key" value="c140e6df-a667-446c-b95a-72e10dd8769f">
                       <div class="row">
                        <div class="form-group">
                            <input type="text" name="first_name" id="details" placeholder=" " required>
                            <label for="fname">First Name<span style="color: red;">*</span></label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" id="details" placeholder=" " required>
                            <label for="lname">Last Name<span style="color: red;">*</span></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <input type="text" name="user_id" id="details" placeholder=" " required>
                            <label for="user_id">User ID<span style="color: red;">*</span></label>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="details" placeholder=" " required>
                            <label for="email">Email Address<span style="color: red;">*</span></label>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <input type="text" name="comment" id="details" placeholder=" " required style="height: 170px;">
                            <label for="comment">Comment<span style="color: red; ">*</span></label>

                        </div>
                    </div>

                    <input type="submit" value="Submit" >


                </form>

            </div>

            <div class="location">
                <h3>Location</h3>
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.0979540595836!2d90.42298167479393!3d23.815115586289842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c64c103a8093%3A0xd660a4f50365294a!2sNorth%20South%20University!5e0!3m2!1sen!2sbd!4v1730113834712!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
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
        position: sticky;
        bottom: 0;
        width: 100%;}
    </style>
    <footer>
        &copy; 2024 Library Management System. All rights reserved.
    </footer>

</body>
</html>
