<?php
define('TITLE', 'Work Order');
define('PAGE', 'work');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the user is already logged in, if yes then redirect him to his/her profile page.
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: RequesterLogin.php");
    exit;
}
if(isset($_POST['view'])){
$sql = "SELECT * FROM assignwork WHERE request_id = {$_POST['id']}";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
}
?>
<div class="col-sm-6 mt-5 mx-3">
<h3 class="text-center mt-5">Assigned Work Details</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td scope="row">Request ID</td>
                        <td><?php echo $row['request_id']; ?></td>
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
                <form action="work.php" class="d-print-none d-inline">
                    <input type="submit"  class="btn btn-secondary" value="Close">
                </form>
            </div>
</div>