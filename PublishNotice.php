<?php
//checked
session_start();
require_once('Connection.php');

if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: signin.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploaded_by = $_POST['uploaded_by'];
    $notice_text = trim($_POST['notice_text']);
    $notice_link = trim($_POST['notice_link']);
    $time_date_uploaded = date("Y-m-d H:i:s");


    if (empty($notice_text)) {
        echo "Notice text is required.";
        exit();
    }


    $query = "INSERT INTO notices (uploaded_by, notice_text, notice_link, time_date_uploaded) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $uploaded_by, $notice_text, $notice_link, $time_date_uploaded);

    if ($stmt->execute()) {
        header("Location: CreateNotice.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
