<?php
define('TITLE', 'Submit Request');
define('PAGE', 'SubmitRequest');
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

// Define variables and initialize them with empty values 
$rInfo = $rDesc = $rName = $rAdd1= $rAdd2 = $rCity = $rState = $rZip = $rEmail = $rMob = $rDate = "";

$rInfoErr = $rDescErr = $rNameErr = $rAdd1Err= $rAdd2Err = $rCityErr = $rStateErr = $rZipErr = $rEmailErr = $rMobErr = $rDateErr = "";
// Processing form data when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Validate request information
    if(empty($_POST["inputRequestInfo"])){
        $rInfoErr = "This field is required";
    } else {
        $rInfo = test_input($_POST['inputRequestInfo']);
    }
    // Validate request description
    if(empty($_POST["inputRequestDescription"])){
        $rDescErr = "This field is required";
    } else {
        $rDesc = test_input($_POST['inputRequestDescription']);
    }

    
    // Validate username
    if (empty($_POST["inputName"])) {
        $rNameErr = "Name is required";
    } else {

        $rName = test_input($_POST["inputName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $rName)) {
            $rNameErr = "Only letters and white space allowed";
        }
    }

    // Validate address 1
    if(empty($_POST["inputAddress1"])){
        $rAdd1Err = "This field is required";
    } else {
        $rAdd1 = test_input($_POST['inputAddress1']);
    }

    // Validate address 2
    if(empty($_POST["inputAddress2"])){
        $rAdd2Err = "This field is required";
    } else {
        $rAdd2 = test_input($_POST['inputAddress2']);
    }

    // Validate city name
    if (empty($_POST["inputCity"])) {
        $rCityErr = "This field is required";
    } else {

        $rCity = test_input($_POST["inputCity"]);
        // check if name only contaThis fieldletters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $rCity)) {
            $rCityErr = "Only letters and white space allowed";
        }
    }


    // Validate state name
    if (empty($_POST["inputCity"])) {
        $rStateErr = "This field is required";
    } else {

        $rState = test_input($_POST["inputCity"]);
        // check if name only contaThis fieldletters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $rCity)) {
            $rStateErr = "Only letters and white space allowed";
        }
    }

    // Validate zip code
    if(empty($_POST["inputZip"])){
        $rZipErr = "This field is required";
    } else {
        $rZip = test_input($_POST['inputZip']);
    }

    // Validate email
    if (empty($_POST["inputEmail"])) {
        $rEmailErr = "Email is required";
      } else {
        $rEmail = test_input($_POST["inputEmail"]);
        // check if e-mail address is well-formed
        if (!filter_var($rEmail, FILTER_VALIDATE_EMAIL)) {
          $rEmailErr= "Invalid email format";
        }
      }

      // Validate mob no
    if(empty($_POST["inputMobile"])){
        $rMobErr = "This field is required";
    } else {
        $rMob = test_input($_POST['inputMobile']);
    }

    // Validate date
    if(empty($_POST["inputDate"])){
        $rDateErr = "This field is required";
    } else {
        $rDate = test_input($_POST['inputDate']);
    }

    // Check input errors before updating the database
    if(empty($rInfoErr) && empty($rDescErr) && empty($rNameErr) && empty($rAdd1Err) && empty($rAdd2Err) && empty($rCityErr) && empty($rStateErr) && empty($rZipErr) && empty($rEmailErr) && empty($rMobErr) && empty($rDateErr)){
        // Prepare an insert statement
        $sql = "INSERT INTO submit_request(request, request_desc, username, add1, add2, city, user_state, zip, email, mob, requester_date) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $conn->prepare($sql)){
            // bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssssisis", $param_req, $param_desc, $param_name, $param_add1, $param_add2, $param_city, $param_state, $param_zip, $param_email, $param_mob, $param_date);

            // set parameters
            $param_req = $rInfo;
            $param_desc = $rDesc;
            $param_name = $rName;
            $param_add1 = $rAdd1;
            $param_add2 = $rAdd2;
            $param_city = $rCity;
            $param_state = $rState;
            $param_zip = $rZip;
            $param_email = $rEmail;
            $param_mob = $rMob;
            $param_date = $rDate;

            // Attemt to execute the prepared statement
            if($stmt->execute()){
                // below msg display on form submit success
                $genid = mysqli_insert_id($conn);
                $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">Request Submitted Successfully Your ' .$genid .' </div>';
                session_start();
                $_SESSION['myid'] = $genid;
                echo "<script>location.href='submitrequestsuccess.php'</script>";
            }  
        }

        // Close statement
        $stmt->close();
    } else {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Submit Your Request</div>';
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
<div class="col-sm-9 col-md-10 mt-5">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mx-5" method="POST">
        <div class="form-group">
            <label for="inputRequestInfo">Request Info</label>
            <input type="text" class="form-control" id="inputRequestInfo" name="inputRequestInfo" value="<?php echo $rInfo; ?>" placeholder="Request Info">
            <span class='help-block'><?php echo $rInfoErr; ?></span>
        </div>
        <div class="form-group">
            <label for="inputRequestDescription">Description</label>
            <input type="text" class="form-control" id="inputRequestDescription" name="inputRequestDescription" value="<?php echo $rDesc; ?>" placeholder="Write Description">
            <span class='help-block'><?php echo $rDescErr; ?></span>
        </div>
        <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" class="form-control" id="inputName" name="inputName" value="<?php echo $rName; ?>" placeholder="Rahul">
            <span class='help-block'><?php echo $rNameErr; ?></span>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputAddress">Address Line 1</label>
                <input type="text" class="form-control" id="inputAddress" name="inputAddress1" value="<?php echo $rAdd1; ?>" placeholder="House No. 123">
                <span class='help-block'><?php echo $rAdd1Err; ?></span>
            </div>
            <div class="form-group col-md-6">
                <label for="inputAddress2">Address Line 2</label>
                <input type="text" class="form-control" id="inputAddress2" name="inputAddress2" value="<?php echo $rAdd2; ?>" placeholder="Railway Colony">
                <span class='help-block'><?php echo $rAdd2Err; ?></span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text" class="form-control" id="inputCity" name="inputCity" value="<?php echo $rCity; ?>">
                <span class='help-block'><?php echo $rCityErr; ?></span>
            </div>
            <div class="form-group col-md-4">
                <label for="input
                    State">State</label>
                <input type="text" class="form-control" id="input
                    State" name="input
                    State" value="<?php echo $rState; ?>">
                    <span class='help-block'><?php echo $rStateErr; ?></span>
            </div>
            <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" class="form-control" id="inputZip" onkeypress="isInputNumber(event)" name="inputZip" value="<?php echo $rZip; ?>">
                <span class='help-block'><?php echo $rZipErr; ?></span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail">Email</label>
                <input type="text" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo $rEmail; ?>">
                <span class='help-block'><?php echo $rEmailErr; ?></span>
            </div>
            <div class="form-group col-md-2">
                    <label for="inputMobile">Mobile</label>
                    <input type="text" class="form-control" id="inputMobile" onkeypress="isInputNumber(event)" name="inputMobile" value="<?php echo $rMob; ?>">
                    <span class='help-block'><?php echo $rMobErr; ?></span>
            </div>
            <div class="form-group col-md-2">
                    <label for="inputDate">Date</label>
                    <input type="date" class="form-control" id="inputDate" name="inputDate" value="<?php echo $rDate; ?>">
                    <span class='help-block'><?php echo $rDateErr; ?></span>
            </div>
        </div>
        <input type="submit" class="btn btn-danger" value="Submit" name="submitrequest">
    </form>

    <!-- below msg display if form submitted success or failed -->
    <?php if(isset($msg)) {echo $msg; } ?>
</div>
<script>
    // Only Number for input fields
    function isInputNumber(evt){
        var ch = String.fromCharCode(evt.which);
        if(!(/[0-9]/.test(ch))){
            evt.preventDefault();
        }
    }
</script>
<?php
require "includes/footer.php";
?>