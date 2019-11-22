<?php
// Include dbConnection.php
require_once "dbConnection.php";

// Define variables and initialize with empty values

$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = "";
$username = $email = $password = $confirmPassword = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty($_POST["rName"])) {
        $usernameErr = "Username is required";
    } else {

        $username = test_input($_POST["rName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $username)) {
            $usernameErr = "Only letters and white space allowed";
        }
    }
    // Validate email
    if (empty($_POST["rEmail"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["rEmail"]);
        // check if e-mail address id well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            // Prepare a selcect statement
            $sql = "SELECT r_login_id FROM requesterlogin_tb WHERE  r_email = ?";
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $email;

                // Attemt to execute the prepared statement
                if ($stmt->execute()) {
                    // store result
                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {
                        $emailErr = "This email is already registered.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty($_POST["rPassword"])) {
        $passwordErr = "Please enter a password";
    } elseif (strlen(trim($_POST["rPassword"])) < 6) {
        $passwordErr = "Passord must have atleast 6 characters.";
    } else {
        $password = test_input($_POST["rPassword"]);
    }

    // Validate confirm password
    if (empty($_POST["rConfirmPassword"])) {
        $confirmPasswordErr = "Please confirm password";
    } else {
        $confirmPassword = test_input($_POST["rConfirmPassword"]);
        if (empty($passwordErr) && ($password != $confirmPassword)) {
            $confirmPasswordErr = "Password did not match";
        }
    }
    // Check input errors before inserting in database
    if (empty($usernameErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        // Prepare an insert statement
        $sql = "INSERT INTO  requesterlogin_tb (r_username, r_email, r_password) VALUES(?,?,?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prapared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attemt to execute the prepared statement
            if ($stmt->execute()) {
                $regmsg = '<div class="alert alert-success mt-2" role="alert"> Account Succefully Created </div>';
                // Redirect to login page
                // header("location: login.php");
            } else {
                $regmsg = '<div class="alert alert-danger mt-2" role="alert"> Unable to Create Account </div>';
            }
        }

        // Close statement
        $stmt->close();
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
<div class="container pt-5" id="registration">
    <h2 class="text-center">Create an Account</h2>
    <p class="text-center">Please fill this form to create an account.</p>
    <div class="row my-4">
        <div class="col-md-6 offset-md-3">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="shadow-lg p-4" method="post">
                <div class="form-group <?php echo (!empty($usernameErr) ? 'has-error' : ''); ?>">
                    <i class="fas fa-user"></i>
                    <label for="rName" class="pl-2 font-weight-bold">Name</label>
                    <input type="text" class="form-control" placeholder="Name" id="rName" name="rName" value="<?php echo $username; ?>">
                    <span class="help-block"> <?php echo $usernameErr; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($emailErr) ? 'has-error' : ''); ?>">
                    <i class="fas fa-user"></i>
                    <label for="rEmail" class="pl-2 font-weight-bold">Email</label>
                    <input type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" id="rEmail" name="rEmail">
                    <!-- Add text-white below if want text color white -->
                    <span class="help-block"> <?php echo $emailErr; ?></span>
                    <small class="form-text">We'll never share your email with anyone else</small>
                </div>
                <div class="form-group <?php echo (!empty($passwordErr) ? 'has-error' : ''); ?>">
                    <i class="fas fa-key"></i>
                    <label for="rPassword" class="pl-2 font-weight-bold">Password</label>
                    <input type="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" id="rPassword" name="rPassword">
                    <span class="help-block"> <?php echo $passwordErr; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirmPasswordErr) ? 'has-error' : ''); ?>">
                    <i class="fas fa-key"></i>
                    <label for="rConfirmPassword" class="pl-2 font-weight-bold">Confirm Password</label>
                    <input type="password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirmPassword; ?>" id="rConfirmPassword" name="rConfirmPassword">
                    <span class="help-block">
                        <?php echo $confirmPasswordErr; ?>
                    </span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-danger mt-5 btn-block shadow-sm font-weight-bold" name="rSignup" value="Sign Up">
                    <input type="reset" class="btn btn-default mt-2 btn-block shadow-sm font-weight-bold" value="Reset">
                    <em style="font-size: 10px;">Note - By clicking Sign Up, you agree to our Terms, Data Policy and Cookie Policy.</em>
                </div>
                <?php if (isset($regmsg)) {
                    echo $regmsg;
                } ?>
                <p>Already have an account? <a href="/Requester/RequesterLogin.php">Login here</a>.</p>


            </form>
        </div>
    </div>
</div>