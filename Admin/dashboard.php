<?php
define('TITLE', 'Dasboard');
define('PAGE', 'dashboard');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is already logged in, if yes then redirect him dashboard
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$sql = "SELECT max(id) FROM submit_request";
$result = $conn->query($sql);
$row = $result->fetch_row();
$submitrequest = $row[0];

$sql = "SELECT max(request_id) FROM assignwork";
$result = $conn->query($sql);
$row = $result->fetch_row();
$assignwork = $row[0];

$sql = "SELECT * FROM technician_tb";
$result = $conn->query($sql);
$totaltech = $result->num_rows;
?>

<div class="col-sm-9 col-md-10">
    <div class="row mx-5 text-center">
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem">
                <div class="card-header">Requests Received</div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $submitrequest; ?>
                    </h4>
                    <a href="requests.php" class="btn text-white">View</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem">
                <div class="card-header">Assigned Work</div>
                <div class="card-body">
                    <h4 class="card-title">
                       <?php echo $assignwork; ?>
                    </h4>
                    <a href="work.php" class="btn text-white">View</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem">
                <div class="card-header">No. of Technician</div>
                <div class="card-body">
                    <h4 class="card-title">
                       <?php echo $totaltech; ?>
                    </h4>
                    <a href="technician.php" class="btn text-white">View</a>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-5 mt-5 text-center">
        <!-- Table -->
        <p class="bg-dark text-white p-2">List of Requesters</p>
        <?php
        $sql = "SELECT * FROM requesterlogin_tb";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo '<table class="table">
            <thead>
            <tr>
            <th scope="col">Requester ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            </tr>
            </thead>
            <tbody>';
            while($row = $result -> fetch_assoc()){
                echo '<tr>';
                echo '<th scope="row">'.$row["r_login_id"].'</th>';
                echo '<td>'.$row["r_username"].'</td>';
                echo '<td>'.$row["r_email"].'</td>';
            }
            echo '</tbody>
        </table>';
        } else {
            echo "0 Results";
        }
        $conn->close();
        ?>
    </div>
</div>
</div>
</div>
<?php
require "includes/footer.php";
?>