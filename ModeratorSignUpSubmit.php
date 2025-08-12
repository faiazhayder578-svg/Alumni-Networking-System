<?php
//checked
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    echo '<script type= "text/javascript"> ;
    alert("An Error Occured");
    window.location.href="ModeratorSignUp.php";
    </script>';
    exit();
}


$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$moderator_id = $_POST["moderator_id"];
$email = $_POST["email"];
$password = $_POST["password"];
$re_password = $_POST["re_password"];
$dob = $_POST["dob"];
$phone_number=$_POST['phone_number'];
$gender = $_POST["gender"];

if ($password!=$re_password) {
 echo '<script type= "text/javascript"> ;
 alert("Passwords do not match");
 window.location.href="ModeratorSignUp.html";
 </script>';
 exit();
}


// Check if duplicate account
$dupesql = "SELECT * FROM moderator where moderator_id='$moderator_id' OR email='$email'";

if($duperaw = mysqli_query($conn,$dupesql))

    {if (mysqli_num_rows($duperaw) > 0) {
        echo '<script type= "text/javascript"> ;
        alert("An account is already created with this moderator id or email");
        window.location.href="ModeratorSignUp.php";
        </script>';
        mysqli_close($conn);
        exit();
    }}
    else
    {
        echo '<script type= "text/javascript"> ;
        alert("An Error Occured");
        window.location.href="ModeratorSignUp.php";
        </script>';
        mysqli_close($conn);
        exit();

    }
// Insert data into table
    $sql = "INSERT INTO moderator (first_name,last_name,moderator_id,email,password,date_of_birth,gender,phone_number)
    VALUES ('$first_name','$last_name','$moderator_id','$email','$password','$dob','$gender','$phone_number')";

    // Check if record was successfully inserted
    if (mysqli_query($conn, $sql)) {
        echo '<script type= "text/javascript"> ;
        alert("Account Created Successfully");
        window.location.href="ModeratorSignUp.php";
        </script>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    ?>
