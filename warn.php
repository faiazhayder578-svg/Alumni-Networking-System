<?php
//checked 18/11/2024//
session_start();
if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
    exit();
}

$location='Moderator&AdminDashboard.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['hidden_value'])) {
        $hidden_value = $_POST['hidden_value'];

        if($hidden_value=='moderator')
        {
            $location='ModeratorList.php';

        }
        else
        {
            $location='UserList.php';
        }
        
    } 
}



require_once('Connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $warned_by = $_SESSION['id'];

    $query = "INSERT INTO warn (warned_by, warned_user_id, warned_reason) VALUES ('$warned_by', '$userId', '$message')";
    
    if (mysqli_query($conn, $query)) {
        echo '<script type="text/javascript">
        alert("Warning Sent Successfully");
        window.location.href="'.$location.'";
        </script>';

    } else {
        echo '<script type="text/javascript">
        alert("Error Occurred");
        window.location.href="'.$location.'";
        </script>';

    }
}
else{
 echo '<script type="text/javascript">
 alert("Error Occurred");
 window.location.href="'.$location.'";
 </script>';

}
mysqli_close($conn);
exit();

?>

