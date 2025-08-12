<?php
//checked 18/11/2024//
session_start();
if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
    exit();
}

$location='Moderator&AdminDashboard.php';
$list="";

if (isset($_GET['list'])) {
    $list=$_GET['list'];
}



if (isset($_SESSION['admin_id']))
{
    if($list=='moderator')
    {
        $location='ModeratorList.php';
    }
    else
    {
        $location='UserList.php';
    }

}
else
{
    $location='UserList.php';
}
require_once('Connection.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script type="text/javascript">
    alert("Invalid ID.");
    window.location.href = "'.$location.'";
    </script>';
    exit();
}

$id = $_GET['id'];
$banned_by = $_SESSION['id'];


$query = "INSERT INTO ban (banned_by, banned_user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $banned_by, $id); 

if ($stmt->execute()) {
    echo '<script type="text/javascript">
    alert("Account Banned Successfully");
    window.location.href="'.$location.'";
    </script>';
} else {
    echo '<script type="text/javascript">
    alert("Error Occurred: ' . addslashes($stmt->error) . '");
    window.location.href="'.$location.'";
    </script>';
}

$stmt->close();
$conn->close();
exit();
?>
