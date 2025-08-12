<?php
//checked 18/11/2024//
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";


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

if ($password != $re_password) {
    echo '<script type= "text/javascript">
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
        echo '<script type= "text/javascript">
        alert("An account is already created with this email or user_id");
        window.location.href="SignUp.html";
        </script>';
        mysqli_close($conn);
        exit();
    }
} 

// Insert data into table
$sql = "INSERT INTO users (first_name, last_name, user_id, email, password, date_of_birth, gender, phone_number)
VALUES ('$first_name', '$last_name', '$user_id', '$email', '$password', '$dob', '$gender', '$phone_number')";

$sql2 = "INSERT INTO statistics (user_id, upload, download, ratio)
VALUES ('$user_id', '0', '0', '0')";

// Check if record was successfully inserted
if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2)) {
    echo '<script type= "text/javascript">
    alert("Account Created Successfully");
    window.location.href="SignUp.html";
    </script>';
} else {
    echo '<script type= "text/javascript">
    alert("Error Occured");
    window.location.href="SignUp.html";
    </script>';
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
exit();
?>
