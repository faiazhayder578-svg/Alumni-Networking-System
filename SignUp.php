<?php
//checked 18/11/2024//
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ans";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$user_id = $_POST["user_id"];
$email = $_POST["email"];
$password = $_POST["password"];
$re_password = $_POST["re_password"];
$dob = $_POST["dob"];
$phone_number = $_POST['phone_number'];
$gender = $_POST["gender"];

// Handle profile picture upload
$profile_picture = null;
$upload_dir = "uploads/profile_pictures/";

// Create upload directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $file_name = $_FILES['profile_picture']['name'];
    $file_size = $_FILES['profile_picture']['size'];
    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate file extension
    if (in_array($file_ext, $allowed_types)) {
        // Validate file size (5MB max)
        if ($file_size <= 5 * 1024 * 1024) {
            // Generate unique filename
            $new_filename = $user_id . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $profile_picture = $new_filename;
            } else {
                echo '<script type="text/javascript">
                alert("Error uploading profile picture");
                window.location.href="SignUp.html";
                </script>';
                exit();
            }
        } else {
            echo '<script type="text/javascript">
            alert("Profile picture size must be less than 5MB");
            window.location.href="SignUp.html";
            </script>';
            exit();
        }
    } else {
        echo '<script type="text/javascript">
        alert("Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed");
        window.location.href="SignUp.html";
        </script>';
        exit();
    }
}

// Password validation
if ($password != $re_password) {
    echo '<script type="text/javascript">
    alert("Passwords do not match");
    window.location.href="SignUp.html";
    </script>';
    exit();
}

// Check if duplicate account
$dupesql = "SELECT * FROM users WHERE user_id='$user_id' OR email='$email'";
$dupesql2 = "SELECT * FROM moderator WHERE moderator_id='$user_id' OR email='$email'";

$duperaw = mysqli_query($conn, $dupesql);
$duperaw2 = mysqli_query($conn, $dupesql2);

if ($duperaw || $duperaw2) {
    if (mysqli_num_rows($duperaw) > 0 || mysqli_num_rows($duperaw2) > 0) {
        // Delete uploaded file if account creation fails
        if ($profile_picture && file_exists($upload_dir . $profile_picture)) {
            unlink($upload_dir . $profile_picture);
        }
        
        echo '<script type="text/javascript">
        alert("An account is already created with this email or user_id");
        window.location.href="SignUp.html";
        </script>';
        mysqli_close($conn);
        exit();
    }
} 

// Insert data into table
$sql = "INSERT INTO users (first_name, last_name, user_id, email, password, date_of_birth, gender, phone_number, profile_picture)
VALUES ('$first_name', '$last_name', '$user_id', '$email', '$password', '$dob', '$gender', '$phone_number', '$profile_picture')";

$sql2 = "INSERT INTO statistics (user_id, upload, download, ratio)
VALUES ('$user_id', '0', '0', '0')";

// Check if record was successfully inserted
if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
    echo '<script type="text/javascript">
    alert("Account Created Successfully");
    window.location.href="SignUp.html";
    </script>';
} else {
    // Delete uploaded file if database insertion fails
    if ($profile_picture && file_exists($upload_dir . $profile_picture)) {
        unlink($upload_dir . $profile_picture);
    }
    
    echo '<script type="text/javascript">
    alert("Error Occurred");
    window.location.href="SignUp.html";
    </script>';
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
exit();
?>