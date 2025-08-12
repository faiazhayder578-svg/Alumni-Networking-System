<?php
//checked
session_start();
require_once('Connection.php');


if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: signin.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_id = $_POST['notice_id'];
    $notice_text = trim($_POST['notice_text']);
    $notice_link = trim($_POST['notice_link']);


    if (empty($notice_text)) {
        echo '<script type= "text/javascript"> ;
        alert("Notice is empty");
        window.location.href="CreateNotice.php";
        </script>';
        $stmt->close();
        exit();
    }

    $query = "UPDATE notices SET notice_text = ?, notice_link = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $notice_text, $notice_link, $notice_id);

    if ($stmt->execute()) {
     echo '<script type= "text/javascript"> ;
     alert("Notice Updated Successfully");
     window.location.href="CreateNotice.php";
     </script>';
     $stmt->close();
     exit();
 } else {
    echo '<script type= "text/javascript"> ;
    alert("Error updating notice");
    window.location.href="CreateNotice.php";
    </script>';
}

$stmt->close();
}
$conn->close();
?>

