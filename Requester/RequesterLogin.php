<?php
// Initialize the session
session_start();

// Chaeck if the user is already logged in, if yes then redirect him to his/her profile page.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: RequesterProfile.php");
    exit;
} 

// Include dbConnection.php file 
require_once "../dbConnection.php";

// Define variables and initialize with empty values
$rEmail = $rPassword = "";
$rEmailErr = $rPassordErr = ""; 

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if Email is empty
    if(empty($_POST["rEmail"])){
        $rEmailErr = "Email is required";
    } else {
        $rEmail = test_data($_POST["rEmail"]);
        // check if email adress id well-formed
        if(!filter_var($rEmail, FILTER_VALIDATE_EMAIL)){
            $rEmailErr = "Invalid email formet";
        } 
    }

    // Check if password is empty
    if(empty($_POST["rPassword"])){
        $rPassordErr = "Password is required";
    } else {
        $rPassword = test_data($_POST["rPassword"]);
    }

    // Validate credentials
    if(empty($rEmailErr) && empty($rPassordErr)){
        // Prepare a select statement
        $sql = "SELECT r_login_id, r_email, r_password FROM requesterlogin_tb WHERE r_email = ?";

        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $rEmail;


            // Attemt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // check if email exists, if yes then verify password
                if($stmt->num_rows == 1){
                    // Bind result variables
                    $stmt->bind_result($id, $rEmail, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($rPassword, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $rEmail;

                            // Redirect user to his profile page
                            header("location: RequesterProfile.php");
                        } else {
                            // Display an error message if password is not valid
                            $rPassordErr = "The password you entered was not valid";
                        }
                    }
                 } else{
                    //  Display an error message if email doesn't exist
                    $rEmailErr = "No account found with that email";
                    }
            } else {
                echo "Oops! Something went wrong. Please try again later";
            }
        }
        // Close statement
        $stmt->close();
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
    <p class="text-center" style="font-size: 20px;"><i class="fas fa-user-secret text-danger"></i><span>Requester Area(Demo)</span>
    </p>
    <div class="container-fluid mb-5">
        <div class="row justify-content-center custom-margin">
            <div class="col-sm-6 col-md-4">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="shadow-lg p-4" method="post">
                <div class="form-group <?php echo (!empty($rEmailErr) ? 'has-error': ''); ?>">
                    <i class="fas fa-user"></i>
                    <label for="email" class="pl-2 font-weight-bold">Email</label>
                    <input type="email" class="form-control" placeholder="Email" id="email" name="rEmail" value="<?php echo $rEmail ?>">
                    <span class="help-block"><?php echo $rEmailErr ?></span>
                    <!-- Add text-white if want text color white -->
                    <small class="form-text">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group <?php echo (!empty($rPasswordErr) ? 'has-error': '');?>">
                    <i class="fas fa-key"></i><label for="pass" class="pl-2 font-weight-bold">Password</label>
                    <input type="password" class="form-control" placeholder="Password" id="pass" name="rPassword">
                    <span class="help-block"><?php echo $rPassordErr ?></span>
                </div>
                <input type="submit" class="btn btn-outline-danger mt-3 btn-block shadow-sm font-weight-bold" value="Login">
                <p>Dont' have an account? <a href="../index.php#registration">Sign up now</a>.</p>
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