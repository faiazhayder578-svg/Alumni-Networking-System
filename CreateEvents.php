<?php 
session_start();
if(!isset($_SESSION['moderator_id']))
{
    header("location: signin.php");
}

require_once('Connection.php');

// Check if connection variable exists with different names and create connection if needed
if (isset($con)) {
    $connection = $con;
} elseif (isset($conn)) {
    $connection = $conn;
} elseif (isset($mysqli)) {
    $connection = $mysqli;
} elseif (isset($db)) {
    $connection = $db;
} else {
    // If no connection variable found, create one (adjust these values to match your database)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "lms"; // Change this to your database name
    
    $connection = new mysqli($servername, $username, $password, $database);
    
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
}

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    $event_title = mysqli_real_escape_string($connection, $_POST['event_title']);
    $event_description = mysqli_real_escape_string($connection, $_POST['event_description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $max_participants = !empty($_POST['max_participants']) ? (int)$_POST['max_participants'] : NULL;
    $event_type = $_POST['event_type'];
    $registration_deadline = !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : NULL;
    $created_by = $_SESSION['moderator_id'];

    // Validate required fields
    if (empty($event_title) || empty($event_date) || empty($event_time) || empty($location)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Check if event date is in the future
        if (strtotime($event_date) < strtotime(date('Y-m-d'))) {
            $error_message = "Event date must be in the future.";
        } else {
            // Insert event into database
            $sql = "INSERT INTO events (event_title, event_description, event_date, event_time, location, max_participants, event_type, registration_deadline, created_by) 
                    VALUES ('$event_title', '$event_description', '$event_date', '$event_time', '$location', " . 
                    ($max_participants ? $max_participants : "NULL") . ", '$event_type', " . 
                    ($registration_deadline ? "'$registration_deadline'" : "NULL") . ", '$created_by')";
            
            if (mysqli_query($connection, $sql)) {
                $success_message = "Event created successfully!";
                // Clear form data
                $_POST = array();
            } else {
                $error_message = "Error creating event: " . mysqli_error($connection);
            }
        }
    }
}

// Get existing events created by this moderator
$moderator_id = $_SESSION['moderator_id'];
$events_sql = "SELECT * FROM events WHERE created_by = '$moderator_id' ORDER BY event_date ASC, event_time ASC";
$events_result = mysqli_query($connection, $events_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Events</title>
    <link href="Design.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Poppins">
</head>
<style>
    .form-container {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.2);
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #375a7f;
        border-color: #375a7f;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .events-table {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #4e73df;
        color: white;
        font-weight: 600;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
</style>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2 style="text-transform: uppercase; text-align: center;">Alumni Networking System</h2>
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
                <h3><i class="fas fa-calendar-plus"></i> Create New Event</h3>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_title">Event Title *</label>
                                    <input type="text" class="form-control" id="event_title" name="event_title" required value="<?php echo isset($_POST['event_title']) ? htmlspecialchars($_POST['event_title']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_type">Event Type</label>
                                    <select class="form-control" id="event_type" name="event_type">
                                        <option value="workshop" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'workshop') ? 'selected' : ''; ?>>Workshop</option>
                                        <option value="seminar" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'seminar') ? 'selected' : ''; ?>>Seminar</option>
                                        <option value="networking" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'networking') ? 'selected' : ''; ?>>Networking</option>
                                        <option value="social" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'social') ? 'selected' : ''; ?>>Social</option>
                                        <option value="career_fair" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'career_fair') ? 'selected' : ''; ?>>Career Fair</option>
                                        <option value="other" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="event_description">Event Description</label>
                            <textarea class="form-control" id="event_description" name="event_description" rows="4" placeholder="Describe the event details..."><?php echo isset($_POST['event_description']) ? htmlspecialchars($_POST['event_description']) : ''; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="event_date">Event Date *</label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required value="<?php echo isset($_POST['event_date']) ? $_POST['event_date'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="event_time">Event Time *</label>
                                    <input type="time" class="form-control" id="event_time" name="event_time" required value="<?php echo isset($_POST['event_time']) ? $_POST['event_time'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registration_deadline">Registration Deadline</label>
                                    <input type="date" class="form-control" id="registration_deadline" name="registration_deadline" value="<?php echo isset($_POST['registration_deadline']) ? $_POST['registration_deadline'] : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="location">Location *</label>
                                    <input type="text" class="form-control" id="location" name="location" required placeholder="Event venue or online platform" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_participants">Max Participants</label>
                                    <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" placeholder="Leave empty for unlimited" value="<?php echo isset($_POST['max_participants']) ? $_POST['max_participants'] : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="create_event" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Event
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Display existing events -->
                <div class="events-table">
                    <h4><i class="fas fa-calendar"></i> Your Created Events</h4>
                    <?php if (mysqli_num_rows($events_result) > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Participants</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                                    <?php
                                    // Get participant count
                                    $event_id = $event['event_id'];
                                    $participant_sql = "SELECT COUNT(*) as count FROM event_participants WHERE event_id = '$event_id'";
                                    $participant_result = mysqli_query($connection, $participant_sql);
                                    $participant_count = mysqli_fetch_assoc($participant_result)['count'];
                                    ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($event['event_title']); ?></strong></td>
                                        <td><span class="badge badge-secondary"><?php echo ucfirst(str_replace('_', ' ', $event['event_type'])); ?></span></td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($event['event_date'])); ?><br>
                                            <small><?php echo date('g:i A', strtotime($event['event_time'])); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                                        <td>
                                            <?php
                                            $status_class = 'badge-success';
                                            if ($event['status'] == 'cancelled') $status_class = 'badge-danger';
                                            if ($event['status'] == 'completed') $status_class = 'badge-secondary';
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($event['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo $participant_count; ?>
                                            <?php if ($event['max_participants']): ?>
                                                / <?php echo $event['max_participants']; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">No events created yet.</p>
                    <?php endif; ?>
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
            position: relative; 
            top: 50px;
            width: 100%;
        }
    </style>
    <footer>
        &copy; 2025 Alumni Networking System. All rights reserved.
    </footer>

</body>
</html>