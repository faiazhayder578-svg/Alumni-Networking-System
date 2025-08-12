<?php
//checked 18/11/2024 //
session_start();
require_once('Connection.php');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $table = 'users';
    $id_column = 'user_id';
    $id = $_SESSION['user_id'];
} elseif (isset($_SESSION['moderator_id'])) {
    $table = 'moderator';
    $id_column = 'moderator_id';
    $id = $_SESSION['moderator_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $table = 'admin';
    $id_column = 'admin_id';
    $id = $_SESSION['admin_id'];
} else {
    die("Invalid session!");
}


$query = "SELECT * FROM $table WHERE $id_column='$id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found!");
}

$current_data = mysqli_fetch_assoc($result);


if ($_POST['current_password'] !== $current_data['password']) {

    echo '<script type= "text/javascript">
    alert("Current password is incorrect!");
    window.location.href="EditProfile.php";
    </script>';
    exit();
    die("");
    
}



$first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : $current_data['first_name'];
$last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : $current_data['last_name'];
$email = !empty($_POST['email']) ? $_POST['email'] : $current_data['email'];
$dob = !empty($_POST['dob']) ? $_POST['dob'] : $current_data['date_of_birth'];
$phone_number = !empty($_POST['phone_number']) ? $_POST['phone_number'] : $current_data['phone_number'];
$gender = !empty($_POST['gender']) ? $_POST['gender'] : $current_data['gender'];


$new_password = $current_data['password'];

if (!empty($_POST['new_password']) && !empty($_POST['re_password'])) {

    if ($_POST['new_password'] === $_POST['re_password']) {

        $new_password = $_POST['new_password'];
    } else {

     echo '<script type= "text/javascript">
     alert("New passwords do not match!");
     window.location.href="EditProfile.php";
     </script>';
     exit();
     die("");
 }
}


$update_query = "UPDATE $table SET first_name='$first_name', last_name='$last_name', email='$email', 
date_of_birth='$dob', phone_number='$phone_number', gender='$gender', password='$new_password' WHERE $id_column='$id'";
if (mysqli_query($conn, $update_query)) {

    echo '<script type= "text/javascript">
    alert("Profile Updated Successfully. Please login again.");
    window.location.href="logout.php";
    </script>';

} else {
    echo '<script type= "text/javascript">
    alert("Error Occured");
    window.location.href="EditProfile.php";
    </script>';

    echo "Error updating profile: " . mysqli_error($conn);
}

mysqli_close($conn);
exit();
?>
