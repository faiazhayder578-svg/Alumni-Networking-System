<?php 
//checked 18/11/2024 //
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("location: signin.php");
}


?>   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Moderator Account</title>
    <link href="Design.css" rel="stylesheet">
    <link href="ModeratorSignUp.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2>Alumni Relationship & Networking System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image">
            </div>
            
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</li>
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
                            <input type="search" placeholder="Search..." name="">
                            <button type="submit">Search</button>
                        </form>
                    </div>

                    <div class="logout_container">
                        <a href="logout.php"><span class="logout">Logout</span></a>
                    </div>
                </div> 

                <div class="inner_container"> 
                    <div class="container">
                        <h2>Create Moderator Account</h2>
                        <form id="registration" method="post" action="ModeratorSignUpSubmit.php">
                            <div class="row">
                                <div class="form-group">
                                    <input type="text" name="first_name" id="details" placeholder=" " required maxlength="20">
                                    <label for="fname">First Name<span style="color: red;">*</span></label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="last_name" id="details" placeholder=" " required maxlength="20">
                                    <label for="lname">Last Name</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <input type="text" name="moderator_id" id="details" placeholder=" " required maxlength="10">
                                    <label for="moderator_id">Moderator ID<span style="color: red;">*</span></label>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" id="details" placeholder=" " required maxlength="50">
                                    <label for="email">Email Address<span style="color: red;">*</span></label>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <input type="password" name="password" id="details" placeholder=" " required maxlength="16">
                                    <label for="password">Password<span style="color: red;">*</span></label>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="re_password" id="details" placeholder=" " required maxlength="16">
                                    <label for="re_password">Re-enter Password<span style="color: red;">*</span></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <input type="date" name="dob" id="details" placeholder=" " required>
                                    <label for="dob">Date of Birth<span style="color: red;">*</span></label>
                                </div>
                                <div class="form-group">
                                    <input type="phone" name="phone_number" id="details" placeholder=" " maxlength="14">
                                    <label for="phone_number">Phone Number</label>
                                </div>
                            </div>
                            <label>Gender:</label>
                            <br>&nbsp; &nbsp; &nbsp;
                            <input type="radio" name="gender" value="M" id="male"> Male
                            &nbsp;&nbsp; 
                            <input type="radio" name="gender" value="F" id="female"> Female
                            <br><br>

                            <input type="submit" value="Create" name="submit" id="submit">
                            <br><br>

                        </form>
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
