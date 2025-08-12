<?php
//checked 18/11/2024 //
session_start();


require_once('Connection.php');


$sql = "SELECT * FROM uploads";
$result = $conn->query($sql);

$uploads = [];

if ($result->num_rows > 0) {
    // Fetch all rows and add them to the $uploads array
    while ($row = $result->fetch_assoc()) {
        $uploads[] = $row;
    }
}

// Return the data as JSON
echo json_encode($uploads);

// Close the connection
$conn->close();
?>
