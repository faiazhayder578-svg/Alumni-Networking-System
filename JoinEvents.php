<?php 
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

// Handle event registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_event'])) {
    $event_id = (int)$_POST['event_id'];
    
    // Check if user is already registered
    $check_sql = "SELECT * FROM event_participants WHERE event_id = '$event_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "You are already registered for this event.";
    } else {
        // Check if event has reached maximum capacity
        $capacity_sql = "SELECT e.max_participants, COUNT(ep.registration_id) as current_participants 
                        FROM events e 
                        LEFT JOIN event_participants ep ON e.event_id = ep.event_id 
                        WHERE e.event_id = '$event_id' 
                        GROUP BY e.event_id";
        $capacity_result = mysqli_query($conn, $capacity_sql);
        $capacity_data = mysqli_fetch_assoc($capacity_result);
        
        if ($capacity_data['max_participants'] && $capacity_data['current_participants'] >= $capacity_data['max_participants']) {
            $error_message = "This event has reached maximum capacity.";
        } else {
            // Register user for the event
            $register_sql = "INSERT INTO event_participants (event_id, user_id) VALUES ('$event_id', '$user_id')";
            if (mysqli_query($conn, $register_sql)) {
                $success_message = "Successfully registered for the event!";
            } else {
                $error_message = "Error registering for event: " . mysqli_error($conn);
            }
        }
    }
}

// Handle event unregistration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unregister_event'])) {
    $event_id = (int)$_POST['event_id'];
    
    $unregister_sql = "DELETE FROM event_participants WHERE event_id = '$event_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $unregister_sql)) {
        $success_message = "Successfully unregistered from the event.";
    } else {
        $error_message = "Error unregistering from event: " . mysqli_error($conn);
    }
}

// Get current date and time for filtering
$current_datetime = date('Y-m-d H:i:s');
$current_date = date('Y-m-d');

// Get upcoming events with registration status
$events_sql = "SELECT e.*, 
               (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.event_id) as current_participants,
               (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.event_id AND ep.user_id = '$user_id') as user_registered
               FROM events e 
               WHERE e.status = 'active' AND e.event_date >= '$current_date'
               ORDER BY e.event_date ASC, e.event_time ASC";
$events_result = mysqli_query($conn, $events_sql);

// Get user's registered events
$my_events_sql = "SELECT e.*, ep.registration_date, ep.attendance_status 
                  FROM events e 
                  JOIN event_participants ep ON e.event_id = ep.event_id 
                  WHERE ep.user_id = '$user_id' 
                  ORDER BY e.event_date ASC, e.event_time ASC";
$my_events_result = mysqli_query($conn, $my_events_sql);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Events</title>
    <link href="Design.css" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
    .events-container {
        padding: 20px;
    }

    .event-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .event-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .event-type {
        background-color: #4e73df;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .event-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .event-detail {
        display: flex;
        align-items: center;
        color: #666;
    }

    .event-detail i {
        margin-right: 8px;
        color: #4e73df;
        width: 16px;
    }

    .event-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .event-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .capacity-info {
        color: #666;
        font-size: 0.9rem;
    }

    .btn {
        padding: 8px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s;
    }

    .btn-primary {
        background-color: #4e73df;
        color: white;
    }

    .btn-primary:hover {
        background-color: #375a7f;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .btn-success {
        background-color: #27ae60;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        cursor: not-allowed;
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

    .section-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #4e73df;
    }

    .section-header h3 {
        margin: 0;
        color: #333;
    }

    .no-events {
        text-align: center;
        color: #666;
        padding: 40px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .registration-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-registered {
        background-color: #d4edda;
        color: #155724;
    }

    .status-attended {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-absent {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
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
                <li><a href="Upload.php"><i class="fas fa-upload"></i>Upload</a></li>
                <li><a href="main_job_page.php"><i class="fas fa-jobs"></i>Jobs</a></li>
                <li><a href="JoinEvents.php"><i class="fas fa-calendar"></i>Events</a></li>
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
                        <input type="search" placeholder="Search events..." name="search">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <a href="logout.php" class="logout">Logout</a>
            </div>

            <div class="events-container">
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

                <!-- My Registered Events -->
                <div class="section-header">
                    <h3><i class="fas fa-calendar-check"></i> My Registered Events</h3>
                </div>

                <?php if (mysqli_num_rows($my_events_result) > 0): ?>
                    <?php while ($my_event = mysqli_fetch_assoc($my_events_result)): ?>
                        <div class="event-card">
                            <div class="event-header">
                                <h4 class="event-title"><?php echo htmlspecialchars($my_event['event_title']); ?></h4>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span class="event-type"><?php echo ucfirst(str_replace('_', ' ', $my_event['event_type'])); ?></span>
                                    <span class="registration-status status-<?php echo $my_event['attendance_status']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $my_event['attendance_status'])); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="event-details">
                                <div class="event-detail">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($my_event['event_date'])); ?>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('g:i A', strtotime($my_event['event_time'])); ?>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($my_event['location']); ?>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-user-plus"></i>
                                    Registered: <?php echo date('M j, Y', strtotime($my_event['registration_date'])); ?>
                                </div>
                            </div>

                            <?php if ($my_event['event_description']): ?>
                                <div class="event-description">
                                    <?php echo htmlspecialchars($my_event['event_description']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="event-actions">
                                <div></div>
                                <?php if ($my_event['status'] == 'active' && strtotime($my_event['event_date']) >= strtotime(date('Y-m-d'))): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="event_id" value="<?php echo $my_event['event_id']; ?>">
                                        <button type="submit" name="unregister_event" class="btn btn-danger" onclick="return confirm('Are you sure you want to unregister from this event?')">
                                            <i class="fas fa-times"></i> Unregister
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-events">
                        <i class="fas fa-calendar-times fa-3x" style="color: #ccc; margin-bottom: 15px;"></i>
                        <p>You haven't registered for any events yet.</p>
                    </div>
                <?php endif; ?>

                <!-- Available Events -->
                <div class="section-header" style="margin-top: 40px;">
                    <h3><i class="fas fa-calendar-alt"></i> Available Events</h3>
                </div>

                <?php if (mysqli_num_rows($events_result) > 0): ?>
                    <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                        <?php
                        $is_registered = $event['user_registered'] > 0;
                        $is_full = $event['max_participants'] && $event['current_participants'] >= $event['max_participants'];
                        $registration_closed = $event['registration_deadline'] && strtotime($event['registration_deadline']) < time();
                        ?>
                        <div class="event-card">
                            <div class="event-header">
                                <h4 class="event-title"><?php echo htmlspecialchars($event['event_title']); ?></h4>
                                <span class="event-type"><?php echo ucfirst(str_replace('_', ' ', $event['event_type'])); ?></span>
                            </div>

                            <div class="event-details">
                                <div class="event-detail">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </div>
                                <?php if ($event['registration_deadline']): ?>
                                    <div class="event-detail">
                                        <i class="fas fa-hourglass-end"></i>
                                        Registration Deadline: <?php echo date('M j, Y', strtotime($event['registration_deadline'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($event['event_description']): ?>
                                <div class="event-description">
                                    <?php echo htmlspecialchars($event['event_description']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="event-actions">
                                <div class="capacity-info">
                                    <i class="fas fa-users"></i>
                                    <?php echo $event['current_participants']; ?>
                                    <?php if ($event['max_participants']): ?>
                                        / <?php echo $event['max_participants']; ?>
                                    <?php endif; ?>
                                    participants
                                </div>

                                <?php if ($is_registered): ?>
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check"></i> Already Registered
                                    </button>
                                <?php elseif ($registration_closed): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-lock"></i> Registration Closed
                                    </button>
                                <?php elseif ($is_full): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-users"></i> Event Full
                                    </button>
                                <?php else: ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                        <button type="submit" name="register_event" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Register
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-events">
                        <i class="fas fa-calendar fa-3x" style="color: #ccc; margin-bottom: 15px;"></i>
                        <p>No events are currently available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        footer {
            background-color: #4e73df;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: relative;
            top: 20px;
            width: 100%;
        }
    </style>
    
    <footer>
        &copy; 2025 Alumni Networking System. All rights reserved.
    </footer>
</body>
</html>