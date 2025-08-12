<?php
//checked 18/11/2024//
session_start();
if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
    exit();
}

require_once('Connection.php');


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script type="text/javascript">
    alert("Invalid ID.");
    window.location.href="UserList.php";
    </script>';
    exit();
}

$id = $_GET['id'];


$query = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id); 

if ($stmt->execute()) {
    echo '<script type="text/javascript">
    alert("Account Deleted Successfully");
    window.location.href="UserList.php";
    </script>';
} else {
    echo '<script type="text/javascript">
    alert("Error Occurred: ' . addslashes($stmt->error) . '");
    window.location.href="UserList.php";
    </script>';
}

$stmt->close();
$conn->close();
exit();
?>
