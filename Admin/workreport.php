<?php
define('TITLE', 'Product Report');
define('PAGE', 'workreport');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is  logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <?php
    $startdateErr = $enddateErr = "";
    $startdate = $enddate = "";
    if (isset($_POST['searchsubmit'])) {
        // Validate start date
        if (empty($_POST['startdate'])) {
            $startdateErr = "This field is required";
        } else {
            $startdate = $_POST['startdate'];
        }

        // validate end date
        if (empty($_POST['enddate'])) {
            $enddateErr = "This field is required";
        } else {
            $enddate = $_POST['enddate'];
        }

        if (empty($startdateErr) && empty($enddateErr)) {
            $sql = "SELECT * FROM assignwork WHERE assign_date BETWEEN '$startdate'
                 AND '$enddate'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo '
                <p class="bg-dark text-white p-2 mt-4">Details</p>
                <table class="table table-striped table-inverse table-responsive">
                     <thead class="thead-inverse">
                         <tr>
                             <th>Req ID</th>
                             <th>Request Info</th>
                             <th>Name</th>
                             <th>Address</th>
                             <th>City</th>
                             <th>Mobile</th>
                             <th>Technician</th>
                             <th>Assigned Date</th>
                         </tr>
                         </thead>
                         <tbody>
                  ';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                        <th scope="row">' . $row['request_id'] . '</th>
                        <td>' . $row['request_info'] . '</td>
                        <td>' . $row['requester_name'] . '</td>
                        <td>' . $row['requester_add1'] . '</td>
                        <td>' . $row['requester_city'] . '</td>
                        <td>' . $row['requester_mobile'] . '</td>
                        <td>' . $row['assign_tech'] . '</td>
                        <td>' . $row['assign_date'] . '</td>
                    </tr>';
                }
                echo '<tr>
                  <td scope="row">
                  <form action="" method="post" class="d-print-none">
                    <input type="submit" value="Print" class="btn btn-danger" onClick="window.print()">
                  </form>
                  </td>
                    </tr>
                    </tbody>
                    </table>';
            } else {
                echo '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">
                     <strong>No Records Found!</strong>
                 </div>';
            }
        }
    }
    ?>
    <form action="" method="post" class="d-print-none">
        <div class="form-row">
            <div class="form-group col-md-2">
                <input type="date" name="startdate" id="startdate" class="form-control">
                <small class="help-block" class="text-muted"><?php echo $startdateErr; ?></small>
            </div><span>to</span>
            <div class="form-group col-md-2">
                <input type="date" name="enddate" id="enddate" class="form-control">
                <small class="help-block" class="text-muted"><?php echo $enddateErr; ?></small>
            </div>
        </div>
        <div class="form-group text-left">
            <input type="submit" value="Search" class="btn btn-secondary" name="searchsubmit">
        </div>
    </form>
</div>
<?php
require "includes/footer.php";
$conn->close();
?>