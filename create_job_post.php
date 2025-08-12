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

// Handle form submission
if(isset($_POST['create_job'])) {
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
    $required_skills = mysqli_real_escape_string($conn, $_POST['required_skills']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $posted_by = $user_id;
    $posted_date = date('Y-m-d H:i:s');
    
    // Check if job_id already exists
    $check_query = "SELECT job_id FROM job_posts WHERE job_id='$job_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $error_message = "Job ID already exists. Please use a different Job ID.";
    } else {
        $insert_query = "INSERT INTO job_posts (job_id, job_title, company_name, job_description, required_skills, salary, location, job_type, deadline, posted_by, posted_date, status) 
                        VALUES ('$job_id', '$job_title', '$company_name', '$job_description', '$required_skills', '$salary', '$location', '$job_type', '$deadline', '$posted_by', '$posted_date', 'active')";
        
        if(mysqli_query($conn, $insert_query)) {
            $success_message = "Job post created successfully!";
        } else {
            $error_message = "Error creating job post: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post - Library Management System</title>
    
    <link href="Design.css" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    
    <style>
        .content-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            width: 100%;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
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

        .required {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }
            
            .form-container {
                padding: 25px 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
                    <h1><i class="fas fa-plus-circle"></i> Create Job Post</h1>
                    <p>Fill in the details to create a new job opportunity</p>
                </div>

                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="job_id">Job ID <span class="required">*</span></label>
                                <input type="text" id="job_id" name="job_id" class="form-control" 
                                       placeholder="Enter unique job ID (e.g., JOB001)" required>
                            </div>
                            <div class="form-group">
                                <label for="job_title">Job Title <span class="required">*</span></label>
                                <input type="text" id="job_title" name="job_title" class="form-control" 
                                       placeholder="Enter job title" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_name">Company Name <span class="required">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                   placeholder="Enter company name" required>
                        </div>

                        <div class="form-group">
                            <label for="job_description">Job Description <span class="required">*</span></label>
                            <textarea id="job_description" name="job_description" class="form-control" 
                                      placeholder="Describe the job role, responsibilities, and requirements..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="required_skills">Required Skills <span class="required">*</span></label>
                            <textarea id="required_skills" name="required_skills" class="form-control" 
                                      placeholder="List the skills required for this position (e.g., PHP, MySQL, JavaScript...)" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="job_type">Job Type <span class="required">*</span></label>
                                <select id="job_type" name="job_type" class="form-control" required>
                                    <option value="">Select job type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Remote">Remote</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Application Deadline <span class="required">*</span></label>
                                <input type="date" id="deadline" name="deadline" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="salary">Salary Range</label>
                                <input type="text" id="salary" name="salary" class="form-control" 
                                       placeholder="e.g., $50,000 - $70,000">
                            </div>
                            <div class="form-group">
                                <label for="location">Location <span class="required">*</span></label>
                                <input type="text" id="location" name="location" class="form-control" 
                                       placeholder="Job location" required>
                            </div>
                        </div>

                        <button type="submit" name="create_job" class="submit-btn">
                            <i class="fas fa-plus-circle"></i> Create Job Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set minimum date to today
        document.getElementById('deadline').min = new Date().toISOString().split('T')[0];
        
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>