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

 $emp_nameErr = $emp_cityErr = $emp_mobileErr =  $emp_emailErr = "";
 $emp_name = $emp_city = $emp_mobile =  $emp_email = "";
if (isset($_POST['empupdate'])) {
    
    // Validate Employer Name
    if (empty($_POST["emp_name"])) {
        $emp_nameErr = "Employer Name is required";
    } else {

        $emp_name = test_input($_POST["emp_name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $emp_name)) {
            $emp_nameErr = "Only letters and white space allowed";
        }
        // Validate city
        if (empty($_POST["emp_city"])) {
            $emp_cityErr = "Employer City is required";
        } else {
            $emp_city = test_input($_POST["emp_city"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/", $emp_name)) {
                $emp_nameErr = "Only letters and white space allowed";
            }
        }

        // Validate mobile
        if (empty($_POST["emp_mobile"])) {
            $emp_mobileErr = "Employer Mobile No. is required";
        } else {
            $emp_mobile = test_input($_POST["emp_mobile"]);
        }

        

        // Validate email
        if (empty($_POST["emp_email"])) {
            $emp_cityErr = "Email is required";
        } else {
            $emp_email = test_input($_POST["emp_email"]);
            
        }
        
        // Check input errors before inserting in database
        if (empty($emp_nameErr) && empty($emp_mobileErr) && empty($emp_cityErr) && empty($emp_emailErr)) {
        $sql = "UPDATE technician_tb SET  emp_name = '$emp_name', emp_city = '$emp_city', emp_mobile = '$emp_mobile', emp_email = '$emp_email' WHERE emp_id = {$_POST['emp_id']}"; 
            if ($conn->query($sql) == TRUE) {
                // below msg display on form submit success
                $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
            } else {
                // below msg display on form submit failed
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
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
    <h3 class="text-center">Update Technician Details</h3>
    
    <?php
    if(isset($_POST['view'])){
    $sql = "SELECT * FROM technician_tb WHERE emp_id = {$_POST['id']}";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    }  
    ?>
    <form action="" method="post">
    <div class="form-group">
            <label for="emp_id">Emp ID</label>
            <input type="text" name="emp_id" id="emp_id" class="form-control" value="<?php if(isset($row['emp_id'])) { echo $row['emp_id'];} ?>" readonly>
        </div>
        <div class="form-group">
            <label for="emp_name">Name</label>
            <input type="text" name="emp_name" id="emp_name" class="form-control" value="<?php if(isset($row['emp_name'])) {echo $row['emp_name'];} ?>">
            <span class="help-block"> <?php echo $emp_nameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="emp_city">City</label>
            <input type="text" name="emp_city" id="emp_city" class="form-control" value="<?php if(isset($row['emp_city'])) {echo $row['emp_city'];} ?>">
            <span class="help-block"> <?php echo $emp_cityErr; ?></span>
        </div>
        <div class="form-group">
            <label for="emp_mobile">Mobile</label>
            <input type="tel" onkeypress="isInputNumber(event)" name="emp_mobile" id="emp_mobile" class="form-control" value="<?php if(isset($row['emp_mobile'])) {echo $row['emp_mobile'];} ?>">
            <span class="help-block"> <?php echo $emp_mobileErr; ?></span>
        </div>
        <div class="form-group">
            <label for="emp_email">Email</label>
            <input type="email" name="emp_email" id="emp_email" class="form-control" value="<?php if(isset($row['emp_email'])){echo $row['emp_email'];}?>">
            <span class="help-block"> <?php echo $emp_emailErr; ?></span>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger" id="empupdate" name="empupdate">Update</button>
            <a href="technician.php" class="btn btn-secondary">Close</a>
        </div>
        <?php if(isset($msg)) {
            echo $msg;
        } ?>
    </form>
</div>
<!-- Only Number for input fields -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
</script>
<?php
require "includes/footer.php";

?>