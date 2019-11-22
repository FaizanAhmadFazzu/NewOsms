<?php 
// Initialize the session
if(session_id() == '') {
    session_start();
  }

// Chaeck if the admin is already logged in, if yes then redirect him dashboard
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_REQUEST['view'])){
$sql = "SELECT * FROM submit_request WHERE id = {$_POST['id']}";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
}
// Assign work Order Request Data going to submit and save on assignwork table
if(isset($_REQUEST['assign'])){
    $id = $_POST['id'];
    $request = $_POST['request'];
    $request_desc = $_POST['request_desc'];
    $username = $_POST['username'];
    $add1 = $_POST['add1'];
    $add2 = $_POST['add2'];
    $city = $_POST['city'];
    $user_state = $_POST['user_state'];
    $zip = $_POST['zip'];
    $email = $_POST['email'];
    $mob = $_POST['mob'];
    $assigntech = $_POST['assigntech'];
    $request_date = $_POST['request_date'];
    // Checking for empty fields
    if(empty($id) || empty($request)  ||empty($request_desc) || empty($username) || empty($add1) || empty($add2) || empty($city) || empty($user_state) || empty($zip) || empty($email) || empty($mob) || empty($assigntech) || empty($request_date)){
        // msg displayed if required field missing
        $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">Fill All Fields</div>';
    } else {
        $sql = "INSERT INTO assignwork (request_id, request_info, request_desc, requester_name, requester_add1, requester_add2, requester_city, requester_state, requester_zip, requester_email, requester_mobile, assign_tech, assign_date) VALUES('$id', '$request', '$request_desc', '$username', '$add1', '$add2', '$city', '$user_state', '$zip', '$email', '$mob', '$assigntech', '$request_date')";
        if($conn->query($sql) == TRUE){
            // below msg display on form success
            $msg = '<div class="alert alert-seccess col-sm-6 ml-5 mt-2" role="alert">Work Assigned Successfully</div>';
        }
            else {
                // below msg display on form sumbit failed
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Assign Work</div>';
            }
        
    }
}

?>
<div class="col-sm-5 mt-5 jumbotron">
    <!-- Main Content area Start Last-->
    <form action="" method="post">
        <h5 class="text-center">Assign work Order Request</h5>
        <div class="form-group">
            <label for="id">Request ID</label>
            <input type="text" name="id" id="id" class="form-control" value="<?php if (isset($row['id'])) {
                                                                                            echo $row['id'];
                                                                                        } ?>" readonly>
        </div>
        <div class="form-group">
            <label for="request">Request Info</label>
            <input type="text" name="request" id="request" value="<?php if (isset($row['request'])) {
                                                                        echo $row['request'];
                                                                    } ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="request_desc">Description</label>
            <input type="text" name="request_desc" id="request_desc" value="<?php if (isset($row['request_desc'])) {
                                                                                echo $row['request_desc'];
                                                                            } ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="username">Name</label>
            <input type="text" name="username" id="username" value="<?php if (isset($row['username'])) {
                                                                        echo $row['username'];
                                                                    } ?>" class="form-control">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="add1">Address Line 1</label>
                <input type="text" name="add1" id="add1" value="<?php if (isset($row['add1'])) {
                                                                    echo $row['add1'];
                                                                } ?>" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="add2">Address Line 2</label>
                <input type="text" name="add2" id="add2" value="<?php if (isset($row['add2'])) {
                                                                    echo $row['add2'];
                                                                } ?>" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="city">City</label>
                <input type="text" name="city" id="city" value="<?php if (isset($row['city'])) {
                                                                    echo $row['city'];
                                                                } ?>" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="user_state">State</label>
                <input type="text" name="user_state" id="user_state" value="<?php if (isset($row['user_state'])) {
                                                                                echo $row['user_state'];
                                                                            } ?>" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="zip">Zip</label>
                <input type="text" name="zip" id="zip" value="<?php if (isset($row['zip'])) {
                                                                    echo $row['zip'];
                                                                } ?>" class="form-control" onkeypress="isInputNumber(event)">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php if (isset($row['email'])) {
                                                                        echo $row['email'];
                                                                    } ?>" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="mob">Mobile</label>
                <input type="text" name="mob" id="mob" value="<?php if (isset($row['mob'])) {
                                                                    echo $row['mob'];
                                                                } ?>" class="form-control" onkeypress="isSetNumber(event)">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="assigntech">Assign to Technician</label>
                <input type="text" name="assigntech" id="assigntech" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="request_date">Date</label>
                <input type="date" name="request_date" id="request_date" class="form-control">
            </div>
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-success" name="assign">Assign</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div> <!-- Main Content area End Last -->
</div> <!-- End Row -->
</div>
<!--End Container -->
<!-- Only Number for input fields -->
<script>
    function isInputNumber(evt) {
        var ch = String.fromCharCode(evt.which);
        if (!(/[0-9]/.test(ch))) {
            evt.preventDefault();
        }
    }
</script>