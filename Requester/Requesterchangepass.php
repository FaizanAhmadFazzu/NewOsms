<?php
define('TITLE', 'Change Password');
define('PAGE', 'Requesterchangepass');
require "includes/header.php";
// Initialize the session
session_start();

// check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true) {
    echo "<script>location.href = 'Requesterlogin.php';</script>";
    exit;
}

// Including dbConnection.php
require "../dbConnection.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate new password
    if (empty($_POST["new_password"])) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen($_POST["new_password"]) < 6) {
        $new_password_err = "Password must have atleast 6 characters.";
    } else {
        $new_password = test_input($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty($_POST['confirm_password'])) {
        $confirm_password_err = "Please confirm the password";
    } else {
        $confirm_password = test_input($_POST['confirm_password']);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match";
        }
    }

    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE requesterlogin_tb SET r_password = ? WHERE r_login_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                $passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Password updated Successfully. </div>';
                session_destroy();
                header("location: RequesterLogin.php");
                exit();
            } else {
                $passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Unable to update password. </div>';
            }
        }
    }
    // Close connection
    $conn->close();
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<div class="col-sm-9 col-md 10">
    <div class="row">
        <div class="col-sm-6">
            } <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-5 mx-5">
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" value="<?php echo $_SESSION['email'];?>" readonly>
                </div>
                <div class="form-group">
                    <label for="inputPassword">New Password</label>
                    <input type="password" class="form-control" id="inputPassword" name="new_password" value=" <?php echo $new_password?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="inputConfirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="inputConfirmPassword">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <input type="submit" class="btn btn-danger mr-4 mt-4" value="Update">
                <input type="reset" class="btn btn-secondary mr-4 mt-4" value="Reset">
                <?php if(isset($passmsg)) {echo $passmsg;} ?>
            </form>
        </div>
    </div>
</div>
<?php
require "includes/footer.php";
?>