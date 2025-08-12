<?php
//checked 18/11/2024 //
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: signin.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $uploaded_by = $_SESSION['user_id'];
    $thumbnail_data = $_POST['thumbnail-data'];

    // Validate file upload
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["book-file"]["name"]);
    $file_path = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    if ($file_type != "pdf" && $file_type != "epub") {
        echo '<script type="text/javascript">
        alert("Only PDF and EPUB files are allowed.");
        window.location.href="Upload.php";
        </script>';
        exit();
    }

    if (move_uploaded_file($_FILES["book-file"]["tmp_name"], $file_path)) {
        $thumbnail_path = null;
        if (!empty($thumbnail_data)) {
            // Clean and decode the base64 data
            $thumbnail_data = str_replace('data:image/png;base64,', '', $thumbnail_data);
            $thumbnail_data = str_replace(' ', '+', $thumbnail_data);
            $thumbnail_data = base64_decode($thumbnail_data);

            if ($thumbnail_data === false) {
                echo '<script type="text/javascript">
                alert("Failed to decode thumbnail data.");
                window.location.href="Upload.php";
                </script>';
                exit();
            }

            $thumbnail_name = pathinfo($file_name, PATHINFO_FILENAME) . "_thumbnail.png";
            $thumbnail_path = $target_dir . $thumbnail_name;

            // Save the thumbnail
            if (file_put_contents($thumbnail_path, $thumbnail_data)) {
                echo "Thumbnail saved successfully!";
            } else {
                echo '<script type="text/javascript">
                alert("Failed to save thumbnail.");
                window.location.href="Upload.php";
                </script>';
                exit();
            }
        }

        // Insert data into the database
        $query = "INSERT INTO uploads (title, author, category, genre, description, file_path, thumbnail_path, uploaded_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $title, $author, $category, $genre, $description, $file_path, $thumbnail_path, $uploaded_by);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">
            alert("Uploaded successfully");
            window.location.href="Upload.php";
            </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Database error: ' . $stmt->error . '");
            window.location.href="Upload.php";
            </script>';
        }

        
        $stmt->close();
    } else {
        echo '<script type="text/javascript">
        alert("Error uploading file.");
        window.location.href="Upload.php";
        </script>';
        exit();
    }

    // Close the database connection
    $conn->close();
}
?>
