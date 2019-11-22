<?php
define('TITLE', 'Add New Requester');
define('PAGE', 'requesters');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is  logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values

$r_usernameErr = $r_emailErr = $r_passwordErr = $r_confirm_passwordErr = "";
$r_username = $r_email = $r_password = $r_confirm_password = "";
if (isset($_POST['reqsubmit'])) {
    // Validate username
    if (empty($_POST["r_username"])) {
        $r_usernameErr = "Username is required";
    } else {

        $r_username = test_input($_POST["r_username"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $r_username)) {
            $r_usernameErr = "Only letters and white space allowed";
        }

        // Validate email
        if (empty($_POST["r_email"])) {
            $r_emailErr = "Email is required";
        } else {
            $r_email = test_input($_POST["r_email"]);
            // check if e-mail address id well-formed
            if (!filter_var($r_email, FILTER_VALIDATE_EMAIL)) {
                $r_emailErr = "Invalid email format";
            } else {
                $sql = "SELECT r_login_id FROM requesterlogin_tb WHERE  r_email = '$r_email'";
                $result = $conn->query($sql);
                if ($result->num_rows == 1) {
                    $r_emailErr = ' This email is already registered';
                } 
            }
        }
        // Validate password
        if (empty($_POST["r_password"])) {
            $r_passwordErr = "Please enter a password";
        } elseif (strlen(trim($_POST["r_password"])) < 6) {
            $r_passwordErr = "Passord must have atleast 6 characters.";
        } else {
            $r_password = test_input($_POST["r_password"]);
        }

        // Validate confirm password
        if (empty($_POST["r_confirm_password"])) {
            $r_confirm_passwordErr = "Please confirm password";
        } else {
            $r_confirm_password = test_input($_POST["r_confirm_password"]);
            if (empty($r_passwordErr) && ($r_password != $r_confirm_password)) {
                $r_confirm_passwordErr = "Password did not match";
            }
        }
        // Check input errors before inserting in database
        if (empty($r_usernameErr) && empty($r_passwordErr) && empty($r_confirm_passwordErr)) {
            // Prepare an insert statement
            $r_password = password_hash($r_password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO  requesterlogin_tb (r_username, r_email, r_password) VALUES('$r_username', '$r_email','$r_password')";
            if ($conn->query($sql) == TRUE) {
                // below msg display on form submit success
                $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Added Successfully </div>';
            } else {
                // below msg display on form submit failed
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Add </div>';
            }
        }
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<div class="jumbotron col-sm-6 mt-5 mx-3">
    <h3 class="text-center">Add New Requester</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="r_username">Name</label>
            <input type="text" name="r_username" id="r_username" class="form-control" >
            <span class="help-block"> <?php echo $r_usernameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="r_email">Email</label>
            <input type="email" name="r_email" id="r_email" class="form-control" >
            <span class="help-block"> <?php echo $r_emailErr; ?></span>
        </div>
        <div class="form-group">
            <label for="r_password">Password</label>
            <input type="password" name="r_password" id="r_password" class="form-control" >
            <span class="help-block"> <?php echo $r_passwordErr; ?></span>
        </div>
        <div class="form-group">
            <label for="r_confirm_password">Confirm Password</label>
            <input type="password" name="r_confirm_password" id="r_confirm_password" class="form-control" >
            <span class="help-block"> <?php echo $r_confirm_passwordErr; ?></span>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger" id="reqsubmit" name="reqsubmit">Submit</button>
            <a href="requesters.php" class="btn btn-secondary">Close</a>
        </div>
        <?php if(isset($msg)) {
            echo $msg;
        } ?>
    </form>
</div>

<?php
require "includes/footer.php";
?>