<?php
//checked 18/11/2024//
require_once 'Connection.php';

if (isset($_GET['id'])) {
    $uploadId = intval($_GET['id']); 

    try {
        // Fetch the file and thumbnail paths for the specific upload ID
        $query = "SELECT file_path, thumbnail_path FROM uploads WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $uploadId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filePath = $row['file_path'];
            $thumbnailPath = $row['thumbnail_path'];

            // Delete the file and thumbnail from the server
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            if ($thumbnailPath && file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }


            $deleteQuery = "DELETE FROM uploads WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $uploadId);

            if ($deleteStmt->execute()) {
             echo '<script type= "text/javascript">
             alert("Deleted Upload Successfully");
             window.location.href="checkUploads.php";
             </script>';
             exit();
         } else {
             echo '<script type= "text/javascript">
             alert("Failed to delete upload from database");
             window.location.href="checkUploads.php";
             </script>';
             exit();
         }
     } else {
         echo '<script type= "text/javascript">
         alert("No upload found with the ID");
         window.location.href="checkUploads.php";
         </script>';
         exit();
     }
 } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
} else {
 echo '<script type= "text/javascript">
 alert("No ID in the request");
 window.location.href="checkUploads.php";
 </script>';
 exit();
}
?>
