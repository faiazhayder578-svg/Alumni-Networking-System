<?php 
session_start();
//checked 18/11/2024 //
if(!isset($_SESSION['user_id']))
{
    header("location: signin.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link href="Design.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">
        <div class="side_container">
            <h2>Library Management System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image">
            </div>

            <ul>
                <li><a href="UserDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
                <li><a href="Books.php"><i class="fas fa-book"></i>Books</a></li>
                <li><a href="Upload.php"><i class="fas fa-upload"></i>Upload</a></li>
                <li><a href="ContactUs.php"><i class="fas fa-address-book"></i>Contact</a></li>
            </ul> 
            <div class="social_media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>


        </div>

        <div class="main_content">
            <div class="header">
                <div class="search_container">
                    <form method="post" action="">
                        <input type="search" placeholder="Search..." name="">
                        <button type="submit">Search</button>
                    </form>
                </div>

                <div class="logout_container">
                    <a href="logout.php"><span class="logout">Logout</span></a>
                </div>
            </div> 



            <main>
                <section class="upload-info">
                    <h2>Upload a Book</h2>
                    <form class="upload-form" method="post" enctype="multipart/form-data" action="handleUpload.php">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" placeholder="Enter title" required>

                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" placeholder="Enter author name" required>

                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" placeholder="Enter category" required>

                        <label for="genre">Genre</label>
                        <input type="text" id="genre" name="genre" placeholder="Enter genre" required>

                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Enter description"></textarea>

                        <label for="book-file">Upload File (Only pdf and epub are allowed)</label>
                        <input type="file" id="book-file" name="book-file" accept=".pdf,.epub" required>

                        <canvas id="pdf-preview" style="display:none;"></canvas>

                        <input type="hidden" id="thumbnail-data" name="thumbnail-data">

                        <button type="submit" class="upload-button">Upload Book</button>
                    </form>
                </section>
            </main>

        </div>
    </div>
    <style>
        footer{
            background-color: #4e73df;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: sticky;
            bottom: 0;
            width: 100%;}
        </style>
        <footer>
            &copy; 2024 Library Management System. All rights reserved.
        </footer>
        <script>
            document.getElementById("book-file").addEventListener("change", function (event) {
                const file = event.target.files[0];
                if (file && file.type === "application/pdf") {
                    const fileReader = new FileReader();
                    fileReader.onload = function () {
                        const typedArray = new Uint8Array(this.result);

                        pdfjsLib.getDocument(typedArray).promise.then(function (pdf) {
                            pdf.getPage(1).then(function (page) {
                                const viewport = page.getViewport({ scale: 1 });
                                const canvas = document.getElementById("pdf-preview");
                                const context = canvas.getContext("2d");
                                canvas.width = viewport.width;
                                canvas.height = viewport.height;

                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport,
                                };

                                page.render(renderContext).promise.then(function () {
                                    const thumbnailData = canvas.toDataURL("image/png");
                                    document.getElementById("thumbnail-data").value = thumbnailData;
                                });
                            });
                        });
                    };
                    fileReader.readAsArrayBuffer(file);
                }
            });
        </script>
    </body>

    <style>
        .header{

           border: 1px solid #e0e4e8;
       }
       .image{
        position: relative;bottom: 50px;
    }
    .wrapper ul{
        position: relative; bottom: 95px;
    }
    .wrapper .social_media{
        position: relative;bottom: 90px;
    }
</style>
</html>
