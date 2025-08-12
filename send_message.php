<?php
//checked 18/11/2024 //
session_start();
if(!isset($_SESSION['user_id']) && !isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id']))
{
    header("location: signin.php");
}

include 'Connection.php';

if (isset($_POST['message'])) {
    $id = $_SESSION['id'];
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO chat_messages (id, message) VALUES ('$id', '$message')";

    if ($conn->query($sql) === TRUE) {
        header("Location: GeneralChat.php"); // Redirect back to the chat page
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
