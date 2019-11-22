<?php
define('TITLE', 'Update Requesters');
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

$r_login_idErr = $r_usernameErr = $r_emailErr =  "";
$r_login_id = $r_username = $r_email =  "";


// Processing form data when form is submitted
if (isset($_POST['reupdate'])) {
    echo "afg";

    // Check if r_login_id is empty
    if (empty($_POST["r_login_id"])) {
        $r_login_idErr = "Requester ID is required";
    } else {

        $r_login_id = test_input($_POST["r_login_id"]);
    }

    // Validate Requester Name
    if (empty($_POST["r_username"])) {
        $r_usernameErr = "Requester Name is required";
    } else {

        $r_username = test_input($_POST["r_username"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $r_username)) {
            $r_usernameErr = "Only letters and white space allowed";
        }
    }
    // Check if Email is empty
    if (empty($_POST["r_email"])) {
        $r_emailErr = "Email is required";
    } else {
        $r_email = test_input($_POST["r_email"]);
        // check if email adress id well-formed
        if (!filter_var($r_email, FILTER_VALIDATE_EMAIL)) {
            $r_emailErr = "Invalid email formet";
        }
    }
    // check if input fiels have no errors
    if (empty($r_login_idErr) && empty($r_usernameErr) && empty($r_emailErr)) {
        $sql = "UPDATE requesterlogin_tb SET r_login_id = '$r_login_id',  r_username = '$r_username', r_email = '$r_email' WHERE r_login_id = '$r_login_id'";
        if ($conn->query($sql) == TRUE) {
            echo "print";
            $msg = '<div class="alert alert-success bcol-sm-6"  role="alert">
            Updated Seccessfully
          </div>';
        } 
    } else {
        $msg = '<div class="alert alert-danger bcol-sm-6"  role="alert">
        Unable to Update
      </div>';
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

<div class="col-sm-6 mt-5 mx-3 jumbotron">
    <h3 class="text-center">Update Requester Details</h3>
    <?php
    if (isset($_POST['view'])) {
        $sql = "SELECT * FROM requesterlogin_tb WHERE r_login_id = {$_POST['id']}";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="r_login_id">Requester ID</label>
            <input type="text" name="r_login_id" id="r_login_id" class="form-control" value="<?php if (isset($row['r_login_id'])) {
                                                                                                    echo $row['r_login_id'];
                                                                                                } ?>">
            <small class="help-block"><?php echo $r_login_idErr; ?></small>
        </div>
        <div class="form-group">
            <label for="r_username">Name</label>
            <input type="text" name="r_username" id="r_username" class="form-control" value="<?php if (isset($row['r_username'])) {
                                                                                                    echo $row['r_username'];
                                                                                                } ?>">
            <small id="helpId" class="help-block"><?php echo $r_usernameErr; ?></small>
        </div>
        <div class="form-group">
            <label for="r_email">Email</label>
            <input type="email" name="r_email" id="r_email" class="form-control" value="<?php if (isset($row['r_email'])) {
                                                                                            echo $row['r_email'];
                                                                                        } ?>">
            <small id="helpId" class="help-block"><?php echo $r_emailErr; ?></small>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger" id="reupdate" name="reupdate">Update</button>
            <a href="requesters.php" class="btn btn-secondary">Close</a>
        </div>
        <?php if (isset($msg)) {
            echo $msg;
        } ?>
    </form>
</div>


<?php
require "includes/footer.php";

?>