<?php 
//checked
session_start();

// Check for different possible session variable names
if(!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']))
{
    header("location: signin.php");
    exit();
}

require_once('Connection.php');

// Handle approval/rejection actions
if(isset($_POST['action']) && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];
    
    // Get moderator ID from session (try different possible session variables)
    $moderator_id = '';
    if(isset($_SESSION['moderator_id'])) {
        $moderator_id = $_SESSION['moderator_id'];
    } elseif(isset($_SESSION['admin_id'])) {
        $moderator_id = $_SESSION['admin_id'];
    } elseif(isset($_SESSION['user_id'])) {
        $moderator_id = $_SESSION['user_id'];
    }
    
    if($action == 'approve') {
        // Approve the job - make it visible to public
        $update_query = "UPDATE job_posts SET status = 'approved', approved_by = ?, approved_date = NOW() WHERE job_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $moderator_id, $job_id);
        
        if($stmt->execute()) {
            $success_message = "Job approved and published successfully! It is now visible to all users.";
        } else {
            $error_message = "Error approving job: " . $conn->error;
        }
        $stmt->close();
    }
    elseif($action == 'reject') {
        // Reject the job
        $update_query = "UPDATE job_posts SET status = 'rejected', approved_by = ?, approved_date = NOW() WHERE job_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $moderator_id, $job_id);
        
        if($stmt->execute()) {
            $success_message = "Job rejected successfully.";
        } else {
            $error_message = "Error rejecting job: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch ALL jobs regardless of status
$all_jobs_query = "SELECT jp.*, 
                          u.first_name, u.last_name, u.email,
                          m.first_name as moderator_fname, m.last_name as moderator_lname
                   FROM job_posts jp 
                   JOIN users u ON jp.posted_by = u.user_id 
                   LEFT JOIN moderator m ON jp.approved_by = m.moderator_id
                   ORDER BY jp.posted_date DESC";
$all_jobs_result = $conn->query($all_jobs_query);

// Get counts for different statuses
$pending_count_query = "SELECT COUNT(*) as count FROM job_posts WHERE status = 'pending'";
$pending_result = $conn->query($pending_count_query);
$pending_count = $pending_result->fetch_assoc()['count'];

$approved_count_query = "SELECT COUNT(*) as count FROM job_posts WHERE status = 'approved'";
$approved_result = $conn->query($approved_count_query);
$approved_count = $approved_result->fetch_assoc()['count'];

$rejected_count_query = "SELECT COUNT(*) as count FROM job_posts WHERE status = 'rejected'";
$rejected_result = $conn->query($rejected_count_query);
$rejected_count = $rejected_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Moderation - Library Management System</title>
    <link href="Design.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Poppins">
</head>

<style>
    .stats-container {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .stat-card {
        flex: 1;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 4px solid #4e73df;
    }
    
    .stat-card.pending {
        border-left-color: #ffc107;
    }
    
    .stat-card.approved {
        border-left-color: #28a745;
    }
    
    .stat-card.rejected {
        border-left-color: #dc3545;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
    
    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .job-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .job-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .job-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .job-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
    
    .job-company {
        color: #666;
        font-size: 1rem;
        margin: 5px 0;
    }
    
    .job-details {
        margin-bottom: 15px;
    }
    
    .job-detail-item {
        margin: 5px 0;
        display: flex;
        align-items: center;
    }
    
    .job-detail-item i {
        width: 20px;
        color: #4e73df;
        margin-right: 10px;
    }
    
    .job-description {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .btn-approve {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-reject {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-approve:hover {
        background-color: #218838;
        transform: translateY(-1px);
    }
    
    .btn-reject:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }
    
    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-approved {
        background-color: #28a745;
        color: white;
    }
    
    .status-rejected {
        background-color: #dc3545;
        color: white;
    }
    
    .status-notice {
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
        font-weight: 500;
    }
    
    .status-notice.approved {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-notice.rejected {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert {
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .no-jobs {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    
    .expired-job {
        opacity: 0.7;
        border-left: 4px solid #dc3545;
    }
    
    .expired-notice {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        font-weight: 500;
    }
    
    footer{
        background-color: #4e73df;
        color: #ffffff;
        text-align: center;
        padding: 10px 0;
        position: relative; 
        top: 50px;
        width: 100%;
    }
</style>

<body>
    <div class="wrapper">
        <div class="side_container">
            <h2 style="text-transform: uppercase; text-align: center;">Library Management System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image" style="width:100%; margin-bottom: 20px;">
            </div>
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="ModeratorOperation.php"><i class="fas fa-user-tie"></i> Moderator Operations</a></li>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i> General Chat</a></li>
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
                <h3><i class="fas fa-briefcase"></i> Job Moderation Panel</h3>
                
                <!-- Statistics Cards -->
                <div class="stats-container">
                    <div class="stat-card pending">
                        <div class="stat-number"><?php echo $pending_count; ?></div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                    <div class="stat-card approved">
                        <div class="stat-number"><?php echo $approved_count; ?></div>
                        <div class="stat-label">Approved & Live</div>
                    </div>
                    <div class="stat-card rejected">
                        <div class="stat-number"><?php echo $rejected_count; ?></div>
                        <div class="stat-label">Rejected</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $all_jobs_result->num_rows; ?></div>
                        <div class="stat-label">Total Jobs</div>
                    </div>
                </div>
                
                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Moderator Instructions:</strong> 
                    Approve jobs to make them visible to all users. Rejected jobs will not be shown to users.
                </div>

                <!-- Display All Jobs -->
                <?php if($all_jobs_result->num_rows > 0): ?>
                    <?php while($job = $all_jobs_result->fetch_assoc()): 
                        // Check if job is expired
                        $is_expired = (strtotime($job['deadline']) < time());
                    ?>
                        <div class="job-card <?php echo $is_expired ? 'expired-job' : ''; ?>">
                            <div class="job-header">
                                <div>
                                    <h4 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h4>
                                    <p class="job-company"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                </div>
                                <?php 
                                    $status = $job['status'];
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    if($is_expired) {
                                        $statusClass = 'status-rejected';
                                        $statusText = 'EXPIRED';
                                    } else {
                                        switch($status) {
                                            case 'pending':
                                                $statusClass = 'status-pending';
                                                $statusText = 'PENDING APPROVAL';
                                                break;
                                            case 'approved':
                                                $statusClass = 'status-approved';
                                                $statusText = 'PUBLISHED & LIVE';
                                                break;
                                            case 'rejected':
                                                $statusClass = 'status-rejected';
                                                $statusText = 'REJECTED';
                                                break;
                                            default:
                                                $statusClass = 'status-pending';
                                                $statusText = strtoupper($status);
                                        }
                                    }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </div>
                            
                            <?php if($is_expired): ?>
                                <div class="expired-notice">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>This job has expired</strong> (Deadline passed)
                                </div>
                            <?php endif; ?>
                            
                            <div class="job-details">
                                <div class="job-detail-item">
                                    <i class="fas fa-user"></i>
                                    <span>Posted by: <?php echo htmlspecialchars($job['first_name'] . ' ' . $job['last_name']); ?></span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($job['email']); ?></span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($job['location']); ?></span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo htmlspecialchars($job['job_type']); ?></span>
                                </div>
                                <?php if(!empty($job['salary'])): ?>
                                <div class="job-detail-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span><?php echo htmlspecialchars($job['salary']); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="job-detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>Deadline: <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                                    <?php if($is_expired): ?>
                                        <strong style="color: #dc3545;">(EXPIRED)</strong>
                                    <?php endif; ?>
                                    </span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Posted: <?php echo date('M d, Y H:i', strtotime($job['posted_date'])); ?></span>
                                </div>
                                <?php if(!empty($job['approved_date'])): ?>
                                <div class="job-detail-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Approved: <?php echo date('M d, Y H:i', strtotime($job['approved_date'])); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if(!empty($job['moderator_fname'])): ?>
                                <div class="job-detail-item">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Approved by: <?php echo htmlspecialchars($job['moderator_fname'] . ' ' . $job['moderator_lname']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="job-description">
                                <h5><i class="fas fa-file-alt"></i> Job Description:</h5>
                                <p><?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
                                
                                <?php if(!empty($job['required_skills'])): ?>
                                    <h6><i class="fas fa-cogs"></i> Required Skills:</h6>
                                    <p><?php echo nl2br(htmlspecialchars($job['required_skills'])); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Action buttons for pending jobs -->
                            <?php if($job['status'] == 'pending' && !$is_expired): ?>
                                <div class="action-buttons">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn-approve" onclick="return confirm('Approve this job? It will be published and visible to all users.')">
                                            <i class="fas fa-check"></i> APPROVE & PUBLISH
                                        </button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn-reject" onclick="return confirm('Reject this job? It will not be visible to users.')">
                                            <i class="fas fa-times"></i> REJECT
                                        </button>
                                    </form>
                                </div>
                            <?php elseif($job['status'] == 'approved' && !$is_expired): ?>
                                <div class="status-notice approved">
                                    <i class="fas fa-eye"></i> <strong>This job is LIVE and visible to all users!</strong>
                                    Users can now apply for this position.
                                </div>
                            <?php elseif($job['status'] == 'rejected'): ?>
                                <div class="status-notice rejected">
                                    <i class="fas fa-eye-slash"></i> <strong>This job has been rejected</strong>
                                    It is not visible to users.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-jobs">
                        <i class="fas fa-briefcase fa-3x" style="color: #666; margin-bottom: 15px;"></i>
                        <h4>No Jobs Found</h4>
                        <p>There are no jobs in the system yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2024 Library Management System. All rights reserved.
    </footer>
</body>
</html>
