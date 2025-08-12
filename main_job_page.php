<?php 
//checked 10/08/2025//
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

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Management - Library Management System</title>
    
    <link href="Design.css" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    
    <style>
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 10px;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.2rem;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .option-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4e73df, #224abe);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .option-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20GC
            border-color: #4e73df;
        }

        .option-card:hover::before {
            transform: scaleX(1);
        }

        .option-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            transition: transform 0.3s ease;
        }

        .option-card:hover .option-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .option-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .option-description {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }

        .option-btn {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .option-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.4);
            text-decoration: none;
            color: white;
        }

        .quick-actions {
            background: linear-gradient(135deg, #f8f9fc 0%, #eef1f7 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-top: 30px;
        }

        .quick-actions h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .quick-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .quick-link {
            background: white;
            color: #4e73df;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #4e73df;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-link:hover {
            background: #4e73df;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }
            
            .options-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header h1 {
                font-size: 2.2rem;
            }
            
            .option-card {
                padding: 30px 20px;
            }
            
            .quick-links {
                flex-direction: column;
                align-items: center;
            }
        }

        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #ecf0f1;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
            border-top: 4px solid #4e73df;
        }
    </style>
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
                        <input type="search" placeholder="Search anything..." name="search">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <a href="logout.php" class="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="content-wrapper">
                <div class="page-header">
                    <h1><i class="fas fa-briefcase"></i> Job Management Center</h1>
                    <p>Choose your job management action</p>
                </div>

                <div class="options-grid">
                    <!-- Job Posts -->
                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <h3 class="option-title">Job Posts</h3>
                        <p class="option-description">
                            Browse all available job opportunities posted by community members. 
                            Find your next career opportunity and view detailed job descriptions.
                        </p>
                        <a href="job_posts_view.php" class="option-btn">
                            <i class="fas fa-eye"></i> View Jobs
                        </a>
                    </div>

                    <!-- Apply Job -->
                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h3 class="option-title">Apply Job</h3>
                        <p class="option-description">
                            Apply for available job positions. Submit your applications and 
                            track your application status in one convenient location.
                        </p>
                        <a href="apply_job.php" class="option-btn">
                            <i class="fas fa-send"></i> Apply Now
                        </a>
                    </div>

                    <!-- Create Job Post -->
                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h3 class="option-title">Create Job Post</h3>
                        <p class="option-description">
                            Post a new job opportunity for others to discover. 
                            Add detailed requirements, skills needed, and deadlines.
                        </p>
                        <a href="create_job_post.php" class="option-btn">
                            <i class="fas fa-edit"></i> Create Post
                        </a>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    <div class="quick-links">
                        <a href="UserDashboard.php" class="quick-link">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="EditProfile.php" class="quick-link">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </a>
                        <a href="GeneralChat.php" class="quick-link">
                            <i class="fas fa-comments"></i> Community Chat
                        </a>
                        <a href="Settings.php" class="quick-link">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2025 Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>