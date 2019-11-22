<?php
define('TITLE', 'Work Order');
define('PAGE', 'work');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is  logged in, if not then redirect him login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

?>
<div class="col-sm-9 col-md-10 mt-5">
    <?php 
     $sql = "SELECT * FROM assignwork";
     $result = $conn->query($sql);
     if($result->num_rows > 0){
        echo '<table class="table">
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Request Info</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Technician</th>
                <th>Assigned Date</th>
                <th>Action</th>
            </tr>
        </thead>';
        while($row = $result->fetch_assoc()){
        echo '<tbody>
            <tr>
                <th scope="row">'. $row['request_id'].'</th>
                <td>'.$row['request_info'].'</td>
                <td>'.$row['requester_name'].'</td>
                <td>'.$row['requester_add1'].'</td>
                <td>'.$row['requester_city'].'</td>
                <td>'.$row['requester_mobile'].'</td>
                <td>'.$row['assign_tech'].'</td>
                <td>'.$row['assign_date'].'</td>
                <td><form action="viewassignwork.php" method="post" class="d-inline"><div class="form-group">
                <input type="hidden" name="id"  class="form-control" value='.$row['request_id'].' aria-describedby="helpId">
                <button type="submit" class="btn btn-warning" name="view" value="View"><i class="far fa-eye"></i></button>
              </div></form>
              <form action="" method="post" class="d-inline"><div class="form-group">
              <input type="hidden" name="id"  class="form-control" value='.$row['request_id'].' aria-describedby="helpId">
              <button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button>
            </div></form>
              </td>
            </tr>';
        }
        echo '</tbody></table>';
     } else {
         echo "0 Result";
     }
    ?>
</div>
</div>
</div>
<?php
require "includes/footer.php";
?>