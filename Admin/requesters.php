<?php
define('TITLE', 'Requesters');
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
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!-- Table -->
    <p class="bg-dark text-white p-2">List of Requesters</p>
    <?php
    $sql = "SELECT * FROM requesterlogin_tb";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table class="table table-striped table-inverse">
        <thead class="thead-inverse">
            <tr>
                <th>Requester ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
        <td scope="row">' . $row['r_login_id'] . '</td>
        <td>' . $row['r_username'] . '</td>
        <td >' . $row['r_email'] . '</td>
        <td><form action="editreq.php" method="post" class="d-inline">
        <input type="hidden" name="id" value=' . $row['r_login_id'] . '>
        <button type="submit" class="btn btn-info mr-3" name="view" value="View"><i class="fas fa-pen"></i></button>
    </form>
    <form action="" method="post" class="d-inline">
        <input type="hidden" name="id" value=' . $row['r_login_id'] . '>
        <button type="submit"  class="btn btn-secondary mr-3" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button>
    </form>
    </td>
    </tr>';
        }
        echo '</tbody>
    </table>';
    } else {
        "0 Result";
    }
    if(isset($_POST['delete'])){
    $sql = "DELETE FROM requesterlogin_tb WHERE r_login_id = {$_POST['id']}";
    if($conn->query($sql) == TRUE){
        // below code will refresh the page after deleting the record
        echo ' <meta http-equiv="refresh" content = "0;URL=? deleted" />';
    } else {
        echo "Unable to Delete";
    }
    }
    ?>
</div>
</div>
<div><a href="insertreq.php" class="btn btn-danger box"><i class="fas fa-plus fa-2x"></i></a>
</div>
</div>
<?php
require "includes/footer.php";
?>