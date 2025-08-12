            .status-legend {
                flex-direction: column;
                align-items: center;
            }<?php 
//checked 10/08/2025//
session_start();
if(!isset($_SESSION['user_id']))
{
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

if (!$row) {
    header("location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get specific job if job_id is passed in URL
$selected_job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';

// Fetch ALL job posts regardless of status
$stmt = $conn->prepare("SELECT jp.job_id, jp.job_title, jp.company_name, jp.location, jp.salary, jp.deadline, jp.status,
                       CONCAT(u.first_name, ' ', u.last_name) AS posted_by_name
                       FROM job_posts jp 
                       LEFT JOIN users u ON jp.posted_by = u.user_id 
                       ORDER BY jp.posted_date DESC");
$stmt->execute();
$job_result = $stmt->get_result();
$stmt->close();

// Handle form submission
if(isset($_POST['apply_job'])) {
    $job_id = trim($_POST['job_id']);
    $resume = trim($_POST['resume']);
    $cover_letter = trim($_POST['cover_letter']);
    
    // Validate required fields
    if(empty($job_id) || empty($resume)) {
        $error_message = "Please select a job and provide your resume.";
    } else {
        // Check if user has already applied for this job
        $check_stmt = $conn->prepare("SELECT application_id FROM job_applications WHERE user_id = ? AND job_id = ?");
        $check_stmt->bind_param("ss", $user_id, $job_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if($check_result->num_rows > 0) {
            $error_message = "You have already applied for this job position.";
        } else {
            // Insert new application
            $insert_stmt = $conn->prepare("INSERT INTO job_applications (user_id, job_id, application_date, resume, cover_letter, status) 
                                         VALUES (?, ?, NOW(), ?, ?, 'pending')");
            $insert_stmt->bind_param("ssss", $user_id, $job_id, $resume, $cover_letter);
            
            if($insert_stmt->execute()) {
                $success_message = "Application submitted successfully! You will be notified about the status.";
                // Clear form data after successful submission
                $selected_job_id = '';
                $resume = '';
                $cover_letter = '';
            } else {
                $error_message = "Error submitting application. Please try again.";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}

// Re-fetch ALL job posts for the dropdown (since we used the result above)
$stmt = $conn->prepare("SELECT job_id, job_title, company_name, status, deadline FROM job_posts 
                       ORDER BY posted_date DESC");
$stmt->execute();
$job_dropdown_result = $stmt->get_result();
$stmt->close();

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Library Management System</title>
    
    <link href="Design.css" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    
    <style>
        .content-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            text-align: center;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }



        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .submit-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            width: 100%;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-help-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .job-info-preview {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }

        .job-info-preview.show {
            display: block;
        }

        .job-info-item {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #495057;
        }

        .job-stats {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }
            
            .form-container {
                padding: 25px;
            }

            .status-legend {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="side_container">
            <h2>Library Management System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="User Image">
            </div>
            <ul>
                <li><a href="UserDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                 
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
                <a href="job_posts_view.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Job Posts
                </a>

                <div class="page-header">
                    <h1><i class="fas fa-paper-plane"></i> Apply for Job</h1>
                    <p>Submit your application for available positions</p>
                </div>

                <div class="job-stats">
                    <h4><i class="fas fa-briefcase"></i> Available Jobs: <?php echo $job_dropdown_result->num_rows; ?></h4>
                    <p>All job positions are shown in the dropdown below</p>
                </div>

                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> 
                        <span><?php echo htmlspecialchars($success_message); ?></span>
                    </div>
                <?php endif; ?>

                <?php if(isset($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <span><?php echo htmlspecialchars($error_message); ?></span>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="job_id">Select Job Position <span class="required">*</span></label>
                            <select id="job_id" name="job_id" class="form-control" required>
                                <option value="">-- Select a job position --</option>
                                <?php while($job = mysqli_fetch_array($job_dropdown_result)): ?>
                                    <option value="<?php echo htmlspecialchars($job['job_id']); ?>" 
                                            <?php echo ($selected_job_id == $job['job_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($job['job_title'] . ' - ' . $job['company_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="form-help-text">Choose the job position you want to apply for</div>
                        </div>

                        <div class="form-group">
                            <label for="resume">Resume / CV <span class="required">*</span></label>
                            <textarea id="resume" name="resume" class="form-control" 
                                      placeholder="Please paste your resume content here, including your education, work experience, skills, and achievements..." 
                                      required><?php echo isset($resume) ? htmlspecialchars($resume) : ''; ?></textarea>
                            <div class="form-help-text">Provide a comprehensive overview of your qualifications, experience, and skills</div>
                        </div>

                        <div class="form-group">
                            <label for="cover_letter">Cover Letter</label>
                            <textarea id="cover_letter" name="cover_letter" class="form-control" 
                                      placeholder="Write a personalized cover letter explaining why you're interested in this position and how you can contribute to the company..."><?php echo isset($cover_letter) ? htmlspecialchars($cover_letter) : ''; ?></textarea>
                            <div class="form-help-text">Optional: Explain why you're the perfect fit for this role</div>
                        </div>

                        <button type="submit" name="apply_job" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit Application</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const jobId = document.getElementById('job_id').value;
            const resume = document.getElementById('resume').value.trim();
            
            if (!jobId) {
                e.preventDefault();
                alert('Please select a job position.');
                document.getElementById('job_id').focus();
                return;
            }
            
            if (!resume) {
                e.preventDefault();
                alert('Please provide your resume.');
                document.getElementById('resume').focus();
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Submitting...</span>';
        });
    </script>
</body>
</html> 