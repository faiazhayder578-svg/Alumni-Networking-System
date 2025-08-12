<?php
//checked 18/11/2024 //
include 'Connection.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    
    $stmt = $conn->prepare("SELECT * FROM uploads WHERE id = ?");
    $stmt->bind_param("i", $productId);  // 'i' is for integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Fetch the product data as an associative array
            $productDetails = $result->fetch_assoc();
            echo json_encode($productDetails);  // Convert to JSON
        } else {
            echo json_encode(null);  // Return null if no product found
        }
    } else {
        // Error executing query
        echo json_encode(['error' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
