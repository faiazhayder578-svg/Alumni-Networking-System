<?php 
//checked 08/10/2025 06:43 PM +06//
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit();
}

require_once('Connection.php');

// Secure user authentication with prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND password = ?");
$stmt->bind_param("ss", $_SESSION['user_id'], $_SESSION['password']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$stmt->close();

// Fetch ALL job posts regardless of status
$stmt = $conn->prepare("SELECT jp.*, 
                       CONCAT(u.first_name, ' ', u.last_name) AS posted_by_name 
                       FROM job_posts jp 
                       LEFT JOIN users u ON jp.posted_by = u.user_id 
                       ORDER BY jp.posted_date DESC");
$stmt->execute();
$job_result = $stmt->get_result();
$stmt->close();

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Job Posts - Alumni Relationship & Networking System</title>
    
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            text-align: center;
        }

        .back-btn {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        .job-stats {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .job-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #17a2b8;
            position: relative;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .job-status {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-approved {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-expired {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        .job-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
            padding-right: 80px; /* Make room for status badge */
        }

        .job-info {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .job-info i {
            margin-right: 10px;
            color: #17a2b8;
            width: 16px;
            text-align: center;
        }

        .job-description {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            color: #495057;
            font-size: 0.9rem;
            line-height: 1.5;
            max-height: 80px;
            overflow: hidden;
            position: relative;
        }

        .job-description::after {
            content: "...";
            position: absolute;
            bottom: 0;
            right: 10px;
            background: #f8f9fa;
            padding-left: 20px;
        }

        .apply-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            transition: all 0.3s ease;
            font-weight: 500;
            width: 100%;
            justify-content: center;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
            text-decoration: none;
            color: white;
        }

        .apply-btn.disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .apply-btn.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .expired-notice {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: center;
            font-weight: 500;
        }

        .no-jobs {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-jobs i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }

            .job-list {
                grid-template-columns: 1fr;
            }

            .job-title {
                font-size: 1.3rem;
            }
        }
    </style>
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
                <li><a href="Books.php"><i class="fas fa-book"></i>Books</a></li>
                <li><a href="Upload.php"><i class="fas fa-upload"></i>Upload</a></li>
                <li><a href="main_job_page.php" class="active"><i class="fas fa-briefcase"></i>Job Posts</a></li>
                <li><a href="Settings.php"><i class="fas fa-cog"></i>Settings</a></li>
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
                <a href="main_job_page.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Job Management
                </a>
                <div class="page-header">
                    <h1><i class="fas fa-list-alt"></i> All Job Posts</h1>
                    <p>Browse all job postings in the system</p>
                </div>

                <div class="job-stats">
                    <h4><i class="fas fa-chart-bar"></i> Total Jobs: <?php echo $job_result->num_rows; ?></h4>
                    <p>Showing all jobs regardless of status or deadline</p>
                </div>

                <div class="job-list">
                    <?php 
                    if ($job_result->num_rows > 0) {
                        while($job = $job_result->fetch_array()): 
                            // Determine job status and if it's expired
                            $is_expired = (strtotime($job['deadline']) < time());
                            $status = $job['status'];
                            
                            // Set status class and text
                            $status_class = '';
                            $status_text = '';
                            
                            if ($is_expired) {
                                $status_class = 'status-expired';
                                $status_text = 'Expired';
                            } else {
                                switch($status) {
                                    case 'active':
                                        $status_class = 'status-active';
                                        $status_text = 'Active';
                                        break;
                                    case 'pending':
                                        $status_class = 'status-pending';
                                        $status_text = 'Pending';
                                        break;
                                    case 'approved':
                                        $status_class = 'status-approved';
                                        $status_text = 'Approved';
                                        break;
                                    case 'rejected':
                                        $status_class = 'status-rejected';
                                        $status_text = 'Rejected';
                                        break;
                                    default:
                                        $status_class = 'status-pending';
                                        $status_text = ucfirst($status);
                                }
                            }
                    ?>
                        <div class="job-card">
                            <div class="job-status <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </div>
                            
                            <h3 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                            
                            <div class="job-info">
                                <i class="fas fa-building"></i> 
                                <span><?php echo htmlspecialchars($job['company_name']); ?></span>
                            </div>
                            
                            <div class="job-info">
                                <i class="fas fa-map-marker-alt"></i> 
                                <span><?php echo htmlspecialchars($job['location']); ?></span>
                            </div>
                            
                            <div class="job-info">
                                <i class="fas fa-briefcase"></i> 
                                <span><?php echo htmlspecialchars($job['job_type']); ?></span>
                            </div>
                            
                            <?php if (!empty($job['salary'])): ?>
                            <div class="job-info">
                                <i class="fas fa-dollar-sign"></i> 
                                <span><?php echo htmlspecialchars($job['salary']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="job-info">
                                <i class="fas fa-calendar-alt"></i> 
                                <span>Deadline: <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                                <?php if ($is_expired): ?>
                                    <strong style="color: #dc3545;">(EXPIRED)</strong>
                                <?php endif; ?>
                                </span>
                            </div>
                            
                            <div class="job-info">
                                <i class="fas fa-user"></i> 
                                <span>Posted by: <?php echo htmlspecialchars($job['posted_by_name'] ? $job['posted_by_name'] : 'Unknown'); ?></span>
                            </div>
                            
                            <div class="job-info">
                                <i class="fas fa-clock"></i> 
                                <span>Posted: <?php echo date('M d, Y H:i', strtotime($job['posted_date'])); ?></span>
                            </div>
                            
                            <?php if (!empty($job['job_description'])): ?>
                            <div class="job-description">
                                <?php echo htmlspecialchars(substr($job['job_description'], 0, 150)); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="job-info">
                                <i class="fas fa-tools"></i> 
                                <span><strong>Required Skills:</strong> <?php echo htmlspecialchars($job['required_skills']); ?></span>
                            </div>
                            
                            <?php if ($is_expired): ?>
                                <div class="expired-notice">
                                    <i class="fas fa-exclamation-triangle"></i> This job posting has expired
                                </div>
                            <?php else: ?>
                                <!-- Apply Now button available for ALL non-expired jobs -->
                                <a href="apply_job.php?job_id=<?php echo urlencode($job['job_id']); ?>" class="apply-btn">
                                    <i class="fas fa-paper-plane"></i> Apply Now
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endwhile;
                    } else {
                    ?>
                        <div class="no-jobs">
                            <i class="fas fa-briefcase"></i>
                            <h3>No Job Posts Found</h3>
                            <p>There are currently no job posts in the system. Please check back later!</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

