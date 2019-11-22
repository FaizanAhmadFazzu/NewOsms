<?php
define('TITLE', 'Status');
define('PAGE', 'CheckStatus');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the user is already logged in, if yes then redirect him to his/her profile page.
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: RequesterLogin.php");
    exit;
}
?>
<div class="col-sm-6 mt-5 mx-3">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-3 form-inline d-print-none">
        <div class="form-group mr-3">
            <label for="checkid">Enter Request ID: </label>
            <input type="text" name="checkid" id="checkid" class="form-control ml-3" onkeypress="isInputNumber(event)" placeholder="" aria-describedby="helpId">
        </div>
        <input type="submit" class="btn btn-danger" value="Search">
    </form>
    <?php
    $requestId = "";
    $requestIdMsg = "";
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // check request id is empty
        if (empty($_POST['checkid'])) {
            $requestIdMsg = "<div class='alert alert-danger mt-4' role='alert'>Fill Request ID</div>";
        } else {
            $requestId = $_POST['checkid'];
            // check Request Id  exists in submit_request table
            $sql = "SELECT * FROM submit_request WHERE id = $requestId";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row['id'] == $requestId) {
                $requestIdMsg = "<div class='alert alert-info mt-4' role='alert'>Your Request is Still Pending</div>";
            } else {
                // check Request Id  exists in assignwork table
                $sql = "SELECT * FROM assignwork WHERE request_id = $requestId";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                if ($row['request_id'] != $requestId) {
                    $requestIdMsg = "<div class='alert alert-danger mt-4' role='alert'>Please Enter Correct Request Id</div>";
                }
            }
        }
        if (empty($requestIdMsg)) { ?>
        <h3 class="text-center mt-5">Assigned Work Details</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td scope="row">Request ID</td>
                        <td><?php echo $requestId; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Request Info</td>
                        <td><?php echo $row['request_info']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Request Description</td>
                        <td><?php echo $row['request_desc']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Name</td>
                        <td><?php echo $row['requester_name']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Address Line 1</td>
                        <td><?php echo $row['requester_add1']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Address Line 2</td>
                        <td><?php echo $row['requester_add2']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">City</td>
                        <td><?php echo $row['requester_city']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">State</td>
                        <td><?php echo $row['requester_state']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Pin Code</td>
                        <td><?php echo $row['requester_zip']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Email</td>
                        <td><?php echo $row['requester_email']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Mobile</td>
                        <td><?php echo $row['requester_mobile']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Technician Name</td>
                        <td><?php echo $row['assign_tech']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Assigned Date</td>
                        <td><?php echo $row['assign_date']; ?></td>
                    </tr>
                    <tr>
                        <td scope="row">Customer Sign</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row">Technician Sign</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <form action="" class="d-print-none d-inline mr-3">
                    <input type="submit" value="Print" class="btn btn-danger" onClick="window.print()">
                </form>
                <form action="" class="d-print-none d-inline">
                    <input type="submit"  class="btn btn-secondary" value="Close">
                </form>
            </div>
    <?php


        }
        echo $requestIdMsg;
    }

    ?>
</div>
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