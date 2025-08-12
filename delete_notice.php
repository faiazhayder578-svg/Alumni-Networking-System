<?php
//checked 18/11/2024//
session_start();
if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
}
require_once('Connection.php');

$id = $_GET['id'];
$query = "DELETE FROM notices WHERE id = $id";
if(mysqli_query($conn, $query))
{
	echo '<script type= "text/javascript"> ;
    alert("Notice Deleted Successfully");
    window.location.href="CreateNotice.php";
    </script>';


}
else{
	echo '<script type= "text/javascript"> ;
    alert("Error Occured");
    window.location.href="CreateNotice.php";
    </script>';
    

}

mysqli_close($conn);
exit();
?>