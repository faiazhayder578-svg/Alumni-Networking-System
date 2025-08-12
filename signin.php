<?php 
// checked 18/11/2024//
session_start();
require_once('Connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
           
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="signin.php">Sign In</a></li>
            </ul>
        </nav>
        <div class="header-content">
            <h1>Sign In</h1>
            <p>Please enter your credentials to sign in.</p>
        </div>
    </header>
    
    <div class="signin-form">
        <h2>Sign In</h2>
        <form action="signin.php" method="post">
            <div class="form-group">
                <label for="id">User ID</label>
                <input type="text" id="id" name="id" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="cta-button">Sign In</button>
        </form>
        <p><a href="#" class="forgot-password">Forgot Password?</a></p> <!-- Forgot Password Link -->
        <p>Don't have an account? <a href="SignUp.html" class="signup-link">Sign Up</a></p> <!-- Sign Up Link -->

        <?php 
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $id = $_POST['id'];
            $password = $_POST['password'];

            //check if user is banned
            $query_ban_users = "SELECT * FROM ban WHERE banned_user_id = ?";
            $stmt_ban_users = mysqli_prepare($conn, $query_ban_users);
            mysqli_stmt_bind_param($stmt_ban_users, 's', $id);
            mysqli_stmt_execute($stmt_ban_users);
            $result_ban_user = mysqli_stmt_get_result($stmt_ban_users);
            $row_ban_user = mysqli_fetch_array($result_ban_user);
            if ( $row_ban_user) {

                echo '<script type="text/javascript">
                alert("You have been Banned");
                window.location.href="signin.php";
                </script>';
                mysqli_close($conn);
                exit();
            }

            //check if moderator is banned
            $query_ban_moderator = "SELECT * FROM ban WHERE banned_user_id = ?";
            $stmt_ban_moderator = mysqli_prepare($conn, $query_ban_moderator);
            mysqli_stmt_bind_param($stmt_ban_moderator, 's', $id);
            mysqli_stmt_execute($stmt_ban_moderator);
            $result_ban_moderator = mysqli_stmt_get_result($stmt_ban_moderator);
            $row_ban_user = mysqli_fetch_array($result_ban_moderator);
            if ( $row_ban_moderator) {

                echo '<script type="text/javascript">
                alert("You have been Banned");
                window.location.href="signin.php";
                </script>';
                mysqli_close($conn);
                exit();
            }

            // Check for user
            $query_user = "SELECT * FROM users WHERE user_id = ? AND password = ?";
            $stmt_user = mysqli_prepare($conn, $query_user);
            mysqli_stmt_bind_param($stmt_user, 'ss', $id, $password);
            mysqli_stmt_execute($stmt_user);
            $result_user = mysqli_stmt_get_result($stmt_user);
            $row_user = mysqli_fetch_array($result_user);

            // Check for moderator
            $query_moderator = "SELECT * FROM moderator WHERE moderator_id = ? AND password = ?";
            $stmt_moderator = mysqli_prepare($conn, $query_moderator);
            mysqli_stmt_bind_param($stmt_moderator, 'ss', $id, $password);
            mysqli_stmt_execute($stmt_moderator);
            $result_moderator = mysqli_stmt_get_result($stmt_moderator);
            $row_moderator = mysqli_fetch_array($result_moderator);

            // Check for admin
            $query_admin = "SELECT * FROM admin WHERE admin_id = ? AND password = ?";
            $stmt_admin = mysqli_prepare($conn, $query_admin);
            mysqli_stmt_bind_param($stmt_admin, 'ss', $id, $password);
            mysqli_stmt_execute($stmt_admin);
            $result_admin = mysqli_stmt_get_result($stmt_admin);
            $row_admin = mysqli_fetch_array($result_admin);

            mysqli_close($conn);

            // Handle successful login for each type
            if ($row_user) {
                $_SESSION['user_id'] = $row_user['user_id'];
                $_SESSION['password'] = $row_user['password'];
                $_SESSION['id'] =$row_user['user_id'];;
                header("Location: UserDashboard.php");
                exit(); // Redirect to user dashboard
            } elseif ($row_moderator) {
                $_SESSION['moderator_id'] = $row_moderator['moderator_id'];
                $_SESSION['password'] = $row_moderator['password'];
                $_SESSION['id'] =$row_moderator['moderator_id'];
                header("Location: Moderator&AdminDashboard.php");
                exit(); // Redirect to moderator dashboard
            } elseif ($row_admin) {
                $_SESSION['admin_id'] = $row_admin['admin_id'];
                $_SESSION['password'] = $row_admin['password'];
                $_SESSION['id'] =$row_admin['admin_id'];
                header("Location: Moderator&AdminDashboard.php");
                exit(); // Redirect to admin dashboard
            } else {
                // Invalid credentials if no match is found
                echo '<script type="text/javascript">
                alert("Invalid ID or Password");
                window.location.href = "signin.php"; // Stay on the same page for retry
                </script>';
            }
        }
        ?>
    </div>

    <footer>
        <p>&copy;  2025 Alumni Networking System. All rights reserved.</p>
    </footer>
</body>
</html>
