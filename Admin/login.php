<?php
// Initialize the session
session_start();

// Chaeck if the admin is already logged in, if yes then redirect him to dashboard.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
} 

// Include dbConnection.php file 
require_once "../dbConnection.php";

// Define variables and initialize with empty values
$aEmail = $aPassword = "";
$aEmailErr = $aPassordErr = ""; 

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if Email is empty
    if(empty($_POST["aEmail"])){
        $aEmailErr = "Email is required";
    } else {
        $aEmail = test_data($_POST["aEmail"]);
        // check if email adress id well-formed
        if(!filter_var($aEmail, FILTER_VALIDATE_EMAIL)){
            $aEmailErr = "Invalid email formet";
        } 
    }

    // Check if password is empty
    if(empty($_POST["aPassword"])){
        $aPassordErr = "Password is required";
    } else {
        $aPassword = test_data($_POST["aPassword"]);
    }

    // Validate credentials
    if(empty($aEmailErr) && empty($aPassordErr)){
        $sql = "SELECT email, a_password FROM adminlogin_tb WHERE email='".$aEmail."' AND a_password='".$aPassword."' limit 1";
        $result = $conn->query($sql);
        if($result->num_rows == 1){
          $_SESSION['loggedin'] = true;
          $_SESSION['aEmail'] = $aEmail;
          // Redirecting to RequesterProfile page on Correct Email and Pass
          echo "<script> location.href='dashboard.php'; </script>";
          exit;
        } else {
          $msg = '<div class="alert alert-warning mt-2" role="alert"> Enter Valid Email and Password </div>';
        }
    }

    // Close connection
    $conn->close();
}
function test_data($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">

    <style>
        .custom-margin {
            margin-top: 8vh;
        }
    </style>
    <title>Login</title>
</head>

<body>
    <div class="mb-3 text-center mt-5" style="font-size: 30px;">
        <i class="fas fa-stethoscope"></i>
        <span>Online Maintenance Management System</span>
    </div>
    <p class="text-center" style="font-size: 20px;"><i class="fas fa-user-secret text-danger"></i><span>Admin Area(Demo)</span>
    </p>
    <div class="container-fluid mb-5">
        <div class="row justify-content-center custom-margin">
            <div class="col-sm-6 col-md-4">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="shadow-lg p-4" method="post">
                <div class="form-group <?php echo (!empty($aEmailErr) ? 'has-error': ''); ?>">
                    <i class="fas fa-user"></i>
                    <label for="email" class="pl-2 font-weight-bold">Email</label>
                    <input type="email" class="form-control" placeholder="Email" id="email" name="aEmail" value="<?php echo $aEmail ?>">
                    <span class="help-block"><?php echo $aEmailErr ?></span>
                    <!-- Add text-white if want text color white -->
                    <small class="form-text">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group <?php echo (!empty($aPasswordErr) ? 'has-error': '');?>">
                    <i class="fas fa-key"></i><label for="pass" class="pl-2 font-weight-bold">Password</label>
                    <input type="password" class="form-control" placeholder="Password" id="pass" name="aPassword">
                    <span class="help-block"><?php echo $aPassordErr ?></span>
                </div>
                <input type="submit" class="btn btn-outline-danger mt-3 btn-block shadow-sm font-weight-bold" value="Login">
                <?php if(isset($msg)) {echo $msg;} ?>
            </form>
            
        </div>
    </div>
    <!-- Bootstrap JavaScript -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <!-- Font Awesome Javascript -->
    <script src="../js/all.min.js"></script>
</body>
    
</html>