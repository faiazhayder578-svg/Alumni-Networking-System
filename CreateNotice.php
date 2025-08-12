<?php
//checked
session_start();
if (!isset($_SESSION['moderator_id']) && !isset($_SESSION['admin_id'])) {
    header("location: signin.php");
}
require_once('Connection.php');

$query = "SELECT * FROM notices ORDER BY time_date_uploaded DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Management</title>
    <link href="Design.css" rel="stylesheet">
    <link href="Notice.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cea56aa947.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Poppins">
</head>
<body>
    <div class="wrapper">
       
        <div class="side_container">
            <h2 style="color: #fff;text-transform:uppercase;text-align:center;font-size:2.1rem; position: relative;bottom: 20px;">Library Management System</h2>
            <div class="image">
                <img src="images/Blank_Image.png" alt="image";>
            </div>
            <ul>
                <li><a href="Moderator&AdminDashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="EditProfile.php"><i class="fas fa-user"></i>Account</a></li>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li><a href="AdminOperation.php"><i class="fas fa-user-tie"></i>Admin Operations</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['moderator_id'])): ?>
                    <li><a href="ModeratorOperation.php"><i class="fas fa-user-tie"></i>Moderator Operations</a></li>
                <?php endif; ?>
                <li><a href="GeneralChat.php"><i class="fas fa-message"></i>General Chat</a></li>
            </ul>
            <div class="social_media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="main_content">
            <!-- Header -->
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

            <div class="inner_container">

                <div class="add_notice_container shadowed_box">
                    <h3>Create Notice</h3>
                    <form method="POST" action="PublishNotice.php">
                        <textarea name="notice_text" placeholder="Enter notice text" required rows="3" class="form_control"></textarea>
                        <input type="url" name="notice_link" placeholder="Enter notice link (optional)" class="form_control">
                        <input type="hidden" name="uploaded_by" value="<?php echo $_SESSION['moderator_id'] ?? $_SESSION['admin_id']; ?>">
                        <button type="submit" name="add_notice" class="btn btn-primary">Publish Notice</button>
                    </form>
                </div>

                
                <div class="notice_list_container">
                    <h3>All Notices</h3>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Uploaded By</th>
                                <th>Notice Text</th>
                                <th>Notice Link</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['uploaded_by']; ?></td>
                                    <td><?php echo $row['notice_text']; ?></td>
                                    <td>
                                        <?php if (!empty($row['notice_link'])): ?>
                                            <a href="<?php echo $row['notice_link']; ?>" target="_blank">View Link</a>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['time_date_uploaded']; ?></td>
                                    <td>

                                        <a href="edit_notice.php?notice_id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete_notice.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php } mysqli_close($conn);?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
